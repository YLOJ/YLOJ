<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ProblemFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

use Chumper\Zipper\Zipper;
use Symfony\Component\Yaml\Yaml;
class ProblemsetController extends Controller {

    public function index()
    {
        $problemset = $this->problemShowListSQL()->paginate(20);
        return view('problemset.list', ['problemset' => $problemset]);
    }

    public function show($id)
    {
        $markdowner = new Markdowner();
        $problem = DB::table('problemset')->where('id', $id)->first();
		if (in_array($id,$this->problemShowList())) {
			if (Storage::disk('data')->exists($id.'/config.yml') && Storage::disk('data')->get($id.'/config.yml')){
				$config=Yaml::parse(Storage::disk('data')->get($id.'/config.yml'));
				if(array_key_exists('time_limit',$config))$time_limit=$config['time_limit'];
				else $time_limit=1000;
				$time_limit.=' ms';

				if(array_key_exists('memory_limit',$config))$memory_limit=$config['memory_limit'];
				else $memory_limit=256000;
				$memory_limit.=' KB';

				if(array_key_exists('input_file',$config))$input_file=$config['input_file'];
				else $input_file='Standard Input';

				if(array_key_exists('output_file',$config))$output_file=$config['output_file'];
				else $output_file='Standard Output';
			}else
				$time_limit=$memory_limit=$input_file=$output_file="data not found!";
            return view('problemset.show', [
                'id' => $id,
                'title' => $problem->title,
                'time_limit' => $time_limit,
                'memory_limit' => $memory_limit,
				'input_file' => $input_file,
				'output_file' => $output_file,
                'content_html' => $markdowner->toHTML($problem->content_md),
				'is_admin' => in_array($id,$this->problemManageList())
            ]);
        } else {
            return redirect('404');
        }
    }

    public function add()
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            return view('problemset.add');
        } else {
            return redirect('404');
        }
    }

    public function add_submit(ProblemFormRequest $request)
    {

		if (Auth::check() && Auth::user()->permission > 0) {
			DB::insert('insert into `problemset` (
				`title`, 
				`content_md`
			) values (?, ?)', [
				$request->input('title'),
				$request->input('content_md'),
			]);

			return redirect(route('problem.index'));
		}
		else return redirect('404');
	}
	public function manager($id)
	{
		if (in_array($id,$this->problemManageList())){
			$manager=array_column(DB::select('select username from problem_manager where problem_id=?',[$id]),'username');
			return view('problemset.manager',[
				'id' => $id,
				'manager' => $manager
			]);
		}
		else return redirect('404');

	}
	public function update_manager(Request $request,$id){
		if (in_array($id,$this->problemManageList())){
			$list=explode("\n",$request->content);
			foreach($list as $one){
				$s=str_replace(array(" ","\n","\r","\r\n"),"",$one);
				if(strlen($s)>1){
					if($s[0]=='+'){
						if(DB::select("select * from problem_manager where problem_id=? and username=?",[$id,substr($s,1)])==false && 
							DB::select("select * from users where name=? and permission>=0",[substr($s,1)])!=false)
							DB::insert("insert into problem_manager (username,problem_id) value(?,?)",[substr($s,1),$id]);
					}
					else if($s[0]=='-'){
						DB::delete('delete from problem_manager where problem_id=? and username=?',[$id,substr($s,1)]);
					}
				}
			}
			return redirect('/problem/edit/manager/'.$id);
		}
		else return redirect('404');
	}
	public function edit($id)
	{
		if (in_array($id,$this->problemManageList())){
			$problem = DB::table('problemset')->where('id', $id)->first();
			return view('problemset.edit', [
				'id' => $id,
				'title' => $problem->title,
				'content_md' => $problem->content_md,
				'visibility' => $problem->visibility
			]);
		} else {
			return redirect('404');
		}
	}

	public function edit_submit(ProblemFormRequest $request, $id)
	{

		if (in_array($id,$this->problemManageList())){
			DB::update(
				"update `problemset` set 
				`title` = ?, 
				`content_md` = ?,
				`visibility` = ?
				where `id` = ?",
				[
					$request->input('title'),
					$request->input('content_md'),
					$request->input('visibility') != null,
					$id,
				]
			);
			DB::update(
				"update `submission` set 
				`problem_name` = ? 
				where `problem_id` = ?",
				[
					$request->input('title'),
					$id,
				]
			);

			return redirect(route('problem.edit', $id));
		}else return redirect('404');
	}

	public function data($id)
	{
		if (in_array($id,$this->problemManageList())){
			if (Storage::disk('data')->exists($id.'-new/log'))
				$log=Storage::disk('data')->get($id.'-new/log');
			else
				$log='';
			if (Storage::disk('data')->exists($id.'/config.yml'))
				$config=Storage::disk('data')->get($id.'/config.yml');
			else
				$config='';

			return view('problemset.data', [ 'id' => $id, 'config'=>$config, 'log'=>$log]);
		} else {
			return redirect('404');
		}
	}
	public function data_format(Request $request, $id){
		if (in_array($id,$this->problemManageList())){
			Storage::disk('data')->put('dataconfig',$id."\n".$request->matchrule);
			exec('cd '.base_path().'/storage/app/data && python3 makedata.py');
			return redirect(route('problem.data', $id));
		} else {
			return redirect('404');
		}
	}
	public function format_check(Request $request, $id){
		if (in_array($id,$this->problemManageList())){
			if($request -> input('check')==1){
				Storage::deleteDirectory('data/'.$id);
				Storage::move('data/'.$id.'-new','data/'.$id);
			}
			return redirect(route('problem.data', $id));
		} else {
			return redirect('404');
		}
	}
	public function data_submit(Request $request, $id)
	{
		if (in_array($id,$this->problemManageList())){
			Storage::deleteDirectory('data/'.$id);
			Storage::makeDirectory('data/'.$id);
			Storage::disk('data') -> put(
				$id . '/data.zip',
				file_get_contents( $request -> file('data') )
			);
			$zipper = new Zipper;
			$zipper -> make(storage_path('app/data/'.$id.'/data.zip')) -> extractTo(storage_path('app/data/'.$id.'/'));
			if (!Storage::disk('data')->exists($id.'/config.yml')){
				Storage::disk('data')->put($id.'/config.yml','');
			}
			return redirect(route('problem.data', $id));
		} else {
			return redirect('404');
		}
	}
	public function save_config(Request $request, $id){
		if (in_array($id,$this->problemManageList())){
			Storage::disk('data') -> put(
				$id . '/config.yml',
				$request -> input('config')
			);
			return redirect(route('problem.data', $id));
		} else {
			return redirect('404');
		}
	}
	public function data_download($id)
	{
		if (in_array($id,$this->problemManageList())){
			return Storage::disk('data')->download("$id/data.zip", "data_$id.zip");
		} else {
			return redirect('404');
		}
	}

	public function delete_problem($id)
	{
		if (Auth::check() && Auth::user()->permission > 0) {
			DB::table('problemset') -> where('id', '=', $id) -> delete();
			DB::table('submission') -> where('problem_id', '=', $id) -> delete();
			Storage::disk('data') -> deleteDirectory($id);
		} else {
			return redirect('404');
		}
	}
}
