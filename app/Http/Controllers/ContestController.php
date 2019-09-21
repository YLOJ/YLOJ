<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ContestFormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
class ContestController extends Controller
{
	public function check($sub, $request, $para, $_para = "", $operator = '=') 
	{
		if ($_para == "") {
			$_para = $para;
		}
		if ($request->has($para) && $request->input($para) != null) {
			return $sub->where($_para, $operator, $request->input($para));
		}
		return $sub;
	}
	public function index()
	{
		return view('contest.list', [
			'running_contests' => $this-> contestShowListSQL()-> where('begin_time', '<=', now()) -> where('end_time', '>', now()) -> paginate(1000),
			'upcoming_contests' => $this-> contestShowListSQL()-> where('begin_time', '>', now()) -> orderby('begin_time', 'asc') -> paginate(1000),
			'past_contests' => $this-> contestShowListSQL()-> where('end_time', '<=', now()) -> orderby('end_time', 'desc') -> paginate(20)
		]);
	}
	public function getProblemList($id){
		$problems=array_column(DB::select('select problem from contest_problems where id=?',[$id]),'problem');
		return $problems;

	}
	public function show($id) 
	{
		if(!in_array($id,$this->contestShowList()))return redirect('404');
		$contest = DB::table('contest')->where('id', $id)->first();
		$list=$this->getProblemList($id);
		$contest->problemset=array();
		foreach($list as $pid){
			$problem=(object)null;
			$problem -> id = $pid;
			$problem -> title = DB::table('problemset')->where('id', $pid)->first()->title;
			$contest->problemset[]=$problem;
		}

		return view('contest.show', ['contest' => $contest,
			'is_admin' => in_array($id,$this->contestManageList())
		]);
	}

	public function add() 
	{
		if ($this->is_admin()) {
			return view('contest.add');
		} else {
			return redirect('404');
		}
	}

	public function add_submit(ContestFormRequest $request) 
	{
		if(!$this->is_admin())return redirect('404');

		$s=$request->input('contest_info');
		if ($s==null)$s="";
		DB::insert('insert into `contest` (
			`title`,
			`contest_info`,
			`begin_time`,
			`end_time`,
			`rule`,
			`visibility`
		) values (?, ?, ?, ?, ?, ?)', [
			$request -> input('title'),
			$s,
			$request -> input('begin_time'),
			$request -> input('end_time'),
			$request -> input('rule'),
			2
		]);

		return redirect(route('contest.index'));
	}

	public function edit($id) 
	{
		if(!in_array($id,$this->contestManageList()))return redirct('404');
		$contest = DB::table('contest') -> where('id', $id) -> first();
		return view('contest.edit', ['contest' => $contest]);
	}

	public function edit_submit(ContestFormRequest $request, $cid) 
	{
		if(!in_array($cid,$this->contestManageList()))return redirct('404');
		$s=$request->input('contest_info');
		if($s==null)$s="";
		DB::update("update `contest` set
			`title` = ?,
			`contest_info` = ?,
			`begin_time` = ?,
			`end_time` = ?,
			`rule` = ?,
			`visibility` = ?
			where `id` = ?", 
			[
				$request -> input('title'),
				$s,
				$request -> input('begin_time'),
				$request -> input('end_time'),
				$request -> input('rule'),
				$request -> input('visibility'),
				$cid
			]
		);

		return redirect(route('contest.index'));
	}
	public function edit_problems($id)
	{
		if (in_array($id,$this->contestManageList())){
			$problems=array_column(DB::select('select problem from contest_problems where id=?',[$id]),'problem');
			$problemset=array();
			foreach($problems as $pid){
				$problem=(object)null;
				$problem -> id = $pid;
				$problem -> title = DB::table('problemset')->where('id', $pid)->first()->title;
				$problemset[]=$problem;
			}
			return view('contest.problemset',[
				'id' => $id,
				'problemset' => $problemset
			]);
		}
		else return redirect('404');

	}
	public function problemset_update(Request $request,$id){
		if (in_array($id,$this->contestManageList())){
			$list=explode("\n",$request->content);
			foreach($list as $one){
				$s=str_replace(array(" ","\n","\r","\r\n"),"",$one);
				if(strlen($s)>1){
					if($s[0]=='+'){
						if(in_array(substr($s,1),$this->problemManageList())){
							if(DB::select("select * from contest_problems where id=? and problem=?",[$id,substr($s,1)])==false && DB::select("select * from problemset where id=?",[substr($s,1)])!=false)
								DB::insert("insert into contest_problems (problem,id) value(?,?)",[substr($s,1),$id]);
						}
					}
					else if($s[0]=='-'){
						DB::delete('delete from contest_problems where id=? and problem=?',[$id,substr($s,1)]);
					}
				}
			}
			return redirect('/contest/edit/problemset/'.$id);
		}
		else return redirect('404');
	}
	public function manager($id)
	{
		if (in_array($id,$this->contestManageList())){
			$manager=array_column(DB::select('select username from contest_manager where contest_id=?',[$id]),'username');
			return view('contest.manager',[
				'id' => $id,
				'manager' => $manager
			]);
		}
		else return redirect('404');

	}
	public function update_manager(Request $request,$id){
		if (in_array($id,$this->contestManageList())){
			$list=explode("\n",$request->content);
			foreach($list as $one){
				$s=str_replace(array(" ","\n","\r","\r\n"),"",$one);
				if(strlen($s)>1){
					if($s[0]=='+'){
						if(DB::select("select * from contest_manager where contest_id=? and username=?",[$id,substr($s,1)])==false && 
							DB::select("select * from users where name=? and permission>=0",[substr($s,1)])!=false)
							DB::insert("insert into contest_manager (username,contest_id) value(?,?)",[substr($s,1),$id]);
					}
					else if($s[0]=='-'){
						DB::delete('delete from contest_manager where contest_id=? and username=?',[$id,substr($s,1)]);
					}
				}
			}
			return redirect('/contest/edit/manager/'.$id);
		}
		else return redirect('404');
	}

	public function showproblem($cid, $pid) 
	{
		if(!(in_array($cid,$this->contestShowList()) && in_array($pid,$this->getProblemList($cid))))
			return redirect('404');
		$markdowner = new Markdowner();
		$contest = DB::table('contest')->where('id', $cid)->first();
		$problem = DB::table('problemset')->where('id', $pid)->first();

		if (!Auth::check()) {
			return redirect('login');
		}
		if (NOW() < $contest->begin_time && !$this->is_admin()) {
			return redirect('404');
		}
		if (Storage::disk('data')->exists($pid.'/config.yml')){
			$config=Yaml::parse(Storage::disk('data')->get($pid.'/config.yml'));
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
		return view('contest.showproblem', [
			'pid' => $pid,
			'title' => '['.$contest->title.'] '.$problem->title,
			'time_limit' => $time_limit,
			'memory_limit' => $memory_limit,
			'input_file' => $input_file,
			'output_file' => $output_file,
			'content_html' => $markdowner->toHTML($problem->content_md),
			'cid' => $cid,
			'ended' => now()>=$contest->end_time
		]);
	}

	public function submitpage($cid, $pid) 
	{
		if(!(in_array($cid,$this->contestShowList()) && in_array($pid,$this->getProblemList($cid))))
			return redirect('404');
		$contest = DB::table('contest')->where('id', $cid)->first();
		if (!Auth::check()) {
			return redirect('login');
		}
		if (!$this->is_admin()) {
			if (NOW() < $contest->begin_time) {
				return redirect('404');
			}
		}

		$title = DB::table('problemset')->where('id',$pid)->first()->title;
		return view('contest.submitpage', ['pid' => $pid,'title' => $title,'cid' => $cid]);
	}

	public function submitcode(Request $request, $cid, $pid) 
	{

        if (!Auth::check()) {
            return redirect('login');
        }
		if(!(in_array($cid,$this->contestShowList()) && in_array($pid,$this->getProblemList($cid))))
			return redirect('404');
		$contest = DB::table('contest')->where('id', $cid)->first();
		if(!in_array($cid,$this->contestManageList()) && now()<$contest->begin_time)
			return redirect('404');
		$xid=DB::table('submission')->insertGetId(
			['problem_id'=>$pid,
            'problem_name'=>DB::select('select * from problemset where id=?',[$pid])[0]->title,
            'user_id'=>Auth::User()->id,
            'user_name'=>Auth::User()->name,
            'result'=>"Waiting",
            'score'=>-1,
            'time_used'=>-1,
            'memory_used'=>-1,
            'source_code'=>$request->input('source_code'),
			'created_at'=>NOW(),
			'contest_id' => $cid]
		);
		Redis::rpush('submission','test '.$xid);
		return redirect('/contest/mysubmission/'.$cid);
	}

	public function submission(Request $request, $id)
	{
		$contest = DB::table('contest')->where('id', $id)->first();

		if (in_array($id,$this->contestManageList())||(NOW()>$contest->end_time && in_array($id,$this->contestShowList()))) {
			$submission = DB::table('submission')->orderby('id', 'desc')->where('contest_id','=',$id);
			$submission = $this->check($submission, $request, 'problem_id');
			$submission = $this->check($submission, $request, 'user_name');
			$submission = $this->check($submission, $request, 'min_score', 'score', '>=');
			$submission = $this->check($submission, $request, 'max_score', 'score', '<=');
			return view('submission.list', ['submissionset' => $submission -> paginate('10')]);
		} else {
			return redirect('404');
		}
	}
	public function mysubmission(Request $request, $id)
	{
		if (!Auth::check()) {
			return redirect('login');
		}
		if(!in_array($id,$this->contestShowList()))return redirect('404');
		$submission = DB::table('submission')->orderby('id', 'desc')->where('contest_id','=',$id)->where('user_name','=',Auth::User()->name);
		$contest = DB::table('contest')->where('id', $id)->first();
		if ($contest->rule==0 && ($contest->begin_time<=NOW() && NOW()<=$contest->end_time))
			return view('contest.mysubmission', ['submissionset' => $submission -> paginate('10'),'BAN'=>1]);
		else return view('contest.mysubmission', ['submissionset' => $submission -> paginate('10'),'BAN'=>0]);
	}

	public function standings($cid)
	{
		$contest = DB::table('contest')->where('id', $cid)->first();
		if (
			($contest->rule!=2 &&(in_array($cid,$this->contestManageList())||(NOW()>$contest->end_time && in_array($cid,$this->contestShowList()))))||
			($contest->rule==2 &&(in_array($cid,$this->contestShowList())))
		) 
		{
			$data = DB::table('submission') -> where('contest_id', $cid) 
								   -> where('created_at', '>=', $contest -> begin_time) -> where('created_at', '<=', $contest -> end_time);

			$standings = $data -> select('user_id') -> groupby('user_id') -> get() -> toarray();

			foreach ($standings as &$user) {
				$user -> result = array();
				$user -> user_name = DB::table('users') -> where('id', $user -> user_id) -> first() -> name;
				$user -> score = 0;
				$user -> time = 0;
			}

			$contest->problemset=$this->getProblemList($cid);

			foreach ($contest -> problemset as $pid) {
				if($contest->rule==2){
					$data = DB::table('submission') -> where('contest_id', $cid) 
									-> where('created_at', '>=', $contest -> begin_time) -> where('created_at', '<=', $contest -> end_time)-> where('problem_id', $pid)->where("result",'Accepted')->orderby('created_at','asc');
					$fb=$data->first()->user_id;
				}

				foreach ($standings as &$user) {
					$data = DB::table('submission') -> where('contest_id', $cid) 
								  -> where('created_at', '>=', $contest -> begin_time) -> where('created_at', '<=', $contest -> end_time)-> where('problem_id', $pid);
					if ($contest -> rule == 1) // IOI rule
						$data = $data -> orderby('score', 'desc') -> orderby('created_at', 'desc');
					else if($contest->rule==0) // OI rule
						$data = $data -> orderby('created_at', 'desc');
					else 
						$data = $data -> orderby('created_at', 'asc');

					if($contest->rule!=2){
						$user -> result[$pid] = $data -> where('user_id', $user -> user_id) -> first();
						if($user->result[$pid])	$user -> score+=$user -> result[$pid] -> score;
					}
					else{
						$xdata=$data->where("user_id",$user->user_id)->get();
						$result= (object)null;
						$result->score=0;
						$result->try=0;
						$result->time=0;
						foreach($xdata as $sub){
							$result->id=$sub->id;
							if($sub->result=="Accepted"){
								$result->score=($user->user_id==$fb?2:1);
								$result->time=
									strtotime($sub->created_at)-strtotime($contest->begin_time)+1200*$result->try;
								$user->time+=$result->time;
								$user->score+=1;
								break;
							}
							else if($sub->result=="Waiting"  || $sub->result=="Running" || $sub->result=="Compiling")break;
							++$result->try;
						}
						$user->result[$pid]=$result;
					}
				}
			}

			foreach ($contest -> problemset as &$problem) {
				$pid = $problem;
				$problem = (object)null;
				$problem -> id = $pid;
				$problem -> title = DB::table('problemset')->where('id', $pid)->first()->title;
			}

			$cmp = function($a, $b) {
				return $a -> score < $b -> score||$a->score==$b->score && $a->time > $b->time;
			};
			usort($standings, $cmp);
			return view('contest.standings', ['standings' => $standings, 'contest' => $contest,'mode'=>$contest->rule]);

		} else {
			return redirect('404');
		}
	}
}
