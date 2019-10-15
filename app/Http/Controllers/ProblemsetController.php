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
use Symfony\Component\Yaml\Exception\ParseException;
class ProblemsetController extends Controller {

    public function index()
    { $problemset = $this->problemShowListSQL()->paginate(50);
        return view('problemset.list', ['problemset' => $problemset]);
    }

    public function show($id)
    {
        $markdowner = new Markdowner();
        $problem = DB::table('problemset')->where('id', $id)->first();
		if (in_array($id,$this->problemShowList())) {
			if (Storage::disk('data')->exists($id.'/config.yml') && Storage::disk('data')->get($id.'/config.yml')){
				try {
					$config=Yaml::parse(Storage::disk('data')->get($id.'/config.yml'));
					if(array_key_exists('type',$config))$type=$config['type'];
					else $type=0;
					if($type==0){
						if(array_key_exists('time_limit_same',$config))
							$sameTL=filter_var($config["time_limit_same"], FILTER_VALIDATE_BOOLEAN);
						else $sameTL=1;

						if(array_key_exists('memory_limit_same',$config))
							$sameML=filter_var($config["memory_limit_same"], FILTER_VALIDATE_BOOLEAN);
						else $sameML=1;

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

						$head=($sameTL?("Time Limit: ".$time_limit."<br>"):"").
							($sameML?("Memory Limit: ".$memory_limit."<br>"):"")."Input File: ".$input_file."<br>Output File: ".$output_file."<br>";
					}
					else if($type==1){
						if(array_key_exists('time_limit',$config))$time_limit=$config['time_limit'];
						else $time_limit=1000;
						$time_limit.=' ms';
		
						if(array_key_exists('memory_limit',$config))$memory_limit=$config['memory_limit'];
						else $memory_limit=256000;
						$memory_limit.=' KB';
		
						$head="Time Limit: ".$time_limit."<br>Memory Limit: ".
							$memory_limit."<br>Type: Interactive(OI)<br>";
					}
				} catch (ParseException $exception) {
   					 $head='Unable to parse the YAML string: '.$exception->getMessage().'<br>';
				}

			}else
				$head="data not found!<br>";
            return view('problemset.show', [
                'id' => $id,
                'title' => $problem->title,
				'head' => $head,
				'content_md' => $problem->content_md,
				'is_admin' => in_array($id,$this->problemManageList())
            ]);
        } else {
            return redirect('404');
        }
    }

    public function add()
    {
        if (Auth::check() && $this->is_admin()) {
            return view('problemset.add');
        } else {
            return redirect('404');
        }
    }

    public function add_submit(ProblemFormRequest $request)
    {

		if (Auth::check() && $this->is_admin()) {
			$content=$request->input('content_md');
			if($content==null)$content="";
			DB::insert('insert into `problemset` (
				`title`, 
				`content_md`,
				`visibility`
			) values (?, ?, ?)', [
				$request->input('title'),
				$content,
				2
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
			$content=$request->input('content_md');
			if($content==null)$content="";
			DB::update(
				"update `problemset` set 
				`title` = ?, 
				`content_md` = ?,
				`visibility` = ?
				where `id` = ?",
				[
					$request->input('title'),
					$content,
					$request->input('visibility'),
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
	public function view_file($id,$file){
		$contests=array_column(DB::table('contest_problems')->where('problem',$id)->get()->toArray(),'id');
		if(
			(in_array($id,$this->problemShowList()) || $this->contestShowListSQL()->where('begin_time','<=',now())->whereIn('id',$contests)->count())&& 
			Storage::disk('uploads')->exists('problems/'.$id.'/'.$file))
			return response()->file(storage_path('app/uploads').'/problems/'.$id.'/'.$file);
		else return redirect('404');
	}
	public function upload($id){
		if(!in_array($id,$this->problemManageList()))return redirect('404');
		$list=Storage::disk('uploads')->files('problems/'.$id.'/');
		$s=strlen('problems/'.$id.'/');
		foreach($list as $loop=>$one){
			$list[$loop]=substr($one,$s);
		}
		return view('problemset.uploadfile',[
			'id'=>$id,
			'filelist'=>$list
		]);
	}
	public function upload_file(Request $request,$id){
		if(!in_array($id,$this->problemManageList()))return redirect('404');
		$file=$request->file('source');
		if($file->isValid()){
			Storage::disk('uploads')->put('problems/'.$id.'/'.$file->getClientOriginalName(),file_get_contents( $request -> file('source') ));
		}
		return redirect('/problem/upload/'.$id);
	}
	public function delete_file($id,$file){
		if(!in_array($id,$this->problemManageList()))return redirect('404');
		if(Storage::disk('uploads')->exists('problems/'.$id.'/'.$file)){
			Storage::disk('uploads')->delete('problems/'.$id.'/'.$file);
		}		
		return redirect('/problem/upload/'.$id);
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
			$type=$request->type."\n";
			if($request->type==1)$type.=$request->header."\n";
			Storage::disk('data')->put('type',$type);
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
			$files = glob(storage_path('app/data/'.$id.'/'));
			$zipper=new Zipper();
			$zipper->make(storage_path('app/data/'.$id.'.zip'))->add($files)->close();
			return Storage::disk('data')->download("$id.zip", "data_$id.zip");
		} else {
			return redirect('404');
		}
	}

	public function data_download_api(Request $request)
	{

		if($request->token == env('DATA_DOWNLOAD_TOKEN')){
			$id=$request->id;
			$files = glob(storage_path('app/data/'.$id.'/'));
			$zipper=new Zipper();
			$zipper->make(storage_path('app/data/'.$id.'.zip'))->add($files)->close();
			return Storage::disk('data')->download("$id.zip", "data_$id.zip");
		} else {
			return redirect('404');
		}
	}
	public function delete_problem($id)
	{
		if (Auth::check() && $this->is_admin()) {
			DB::table('problemset') -> where('id', '=', $id) -> delete();
			DB::table('submission') -> where('problem_id', '=', $id) -> delete();
			Storage::disk('data') -> deleteDirectory($id);
		} else {
			return redirect('404');
		}
	}

    public function show_solution($id)
    {
        $problem = DB::table('problemset')->where('id', $id)->first();
		$contests=array_column(DB::table('contest_problems')->where('problem',$id)->get()->toArray(),'id');
		if((in_array($id,$this->problemShowList()) || $this->contestShowListSQL()->where('end_time','<=',now())->whereIn('id',$contests)->count()))
            return view('solution.show', [
                'id' => $id,
                'title' => $problem->title,
				'content_md' => $problem->solution,
				'is_admin' => in_array($id,$this->problemManageList())
            ]);
         else 
            return redirect('404');
    }
	public function view_solution_file($id,$file){
		$contests=array_column(DB::table('contest_problems')->where('problem',$id)->get()->toArray(),'id');
		if(
			(in_array($id,$this->problemShowList()) || $this->contestShowListSQL()->where('end_time','<=',now())->whereIn('id',$contests)->count())&& 
			Storage::disk('uploads')->exists('solution/'.$id.'/'.$file))
			return response()->file(storage_path('app/uploads').'/solution/'.$id.'/'.$file);
		else return redirect('404');
	}
	public function solution_edit($id)
	{
		if (in_array($id,$this->problemManageList())){
			$problem = DB::table('problemset')->where('id', $id)->first();
			return view('solution.edit', [
				'id' => $id,
				'title' => $problem->title,
				'content_md' => $problem->solution,
			]);
		} else {
			return redirect('404');
		}
	}

	public function solution_edit_submit(Request $request, $id)
	{
		if (in_array($id,$this->problemManageList())){
			DB::update(
				"update `problemset` set 
				`solution` = ?
				where `id` = ?",
				[
					$request->input('content_md'),
					$id,
				]
			);
			return redirect('/problem/solution/edit/'.$id);
		}else return redirect('404');
	}
	public function solution_upload($id){
		if(!in_array($id,$this->problemManageList()))return redirect('404');
		$list=Storage::disk('uploads')->files('solution/'.$id.'/');
		$s=strlen('solution/'.$id.'/');
		foreach($list as $loop=>$one){
			$list[$loop]=substr($one,$s);
		}
		return view('solution.uploadfile',[
			'id'=>$id,
			'filelist'=>$list
		]);
	}
	public function solution_upload_file(Request $request,$id){
		if(!in_array($id,$this->problemManageList()))return redirect('404');
		$file=$request->file('source');
		if($file->isValid()){
			Storage::disk('uploads')->put('solution/'.$id.'/'.$file->getClientOriginalName(),file_get_contents( $request -> file('source') ));
		}
		return redirect('/problem/solution/upload/'.$id);
	}
	public function solution_delete_file($id,$file){
		if(!in_array($id,$this->problemManageList()))return redirect('404');
		if(Storage::disk('uploads')->exists('solution/'.$id.'/'.$file)){
			Storage::disk('uploads')->delete('solution/'.$id.'/'.$file);
		}		
		return redirect('/problem/solution/upload/'.$id);
	}


}
