<?php

namespace App\Http\Controllers;

use App\Services\Parsedown;
use App\Http\Requests\ContestFormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
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
		$contest = DB::table('contest')->where('id', $cid)->first();
		$problem = DB::table('problemset')->where('id', $pid)->first();

		if (!Auth::check()) {
			return redirect('login');
		}
		if (NOW() < $contest->begin_time && !in_array($cid,$this->contestManageList())) {
			return redirect('404');
		}
		if (Storage::disk('data')->exists($pid.'/config.yml') && Storage::disk('data')->get($pid.'/config.yml')){
			try {
				$config=Yaml::parse(Storage::disk('data')->get($pid.'/config.yml'));
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
		
		$Parsedown = new Parsedown();
		return view('contest.showproblem', [
			'pid' => $pid,
			'title' => '['.$contest->title.'] '.$problem->title,
			'cid' => $cid,
			'head' => $head,
			'content' => $Parsedown->text($problem->content_md),
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
            'code_length'=>strlen($request->input('source_code')),
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
			(($contest->rule==2 ||$contest->rule==3) &&(in_array($cid,$this->contestShowList())))
		) 
		{
			$data = DB::table('submission') -> where('contest_id', $cid) 
								   -> where('created_at', '>=', $contest -> begin_time)->orderby("id","asc")->get()->toarray();
			$standing=array();
			$contest->problemset=$this->getProblemList($cid);
			$fb=array();
			foreach($contest->problemset  as $pid)$fb[$pid]=false;
			foreach($data as $submission){
				if(!in_array($submission->problem_id,$contest->problemset))continue;
				$name=$submission->user_name;
				if(!array_key_exists($name,$standing)){
					$standing[$name]=(object)null;	
					$nickname=DB::table("users")->where("name",$name);
					if($nickname->exists())
						$standing[$name]->nickname=$nickname->first()->nickname;
					else $standing[$name]->nickname="";
					$standing[$name]->result=array();
					if($submission->created_at<=$contest->end_time)$standing[$name]->in_contest=1;
					else $standing[$name]->in_contest=0;
					foreach($contest->problemset as $pid){
						$result_problem=&$standing[$name]->result[$pid];
						$result_problem=(object)null;
						$result_problem->score=0;
						$result_problem->score_after=0;
						$result_problem->id_after=0;
						$result_problem->time=0;
						$result_problem->fb=0;
						$result_problem->id=0;
						$result_problem->try=0;
					}
					$standing[$name]->score=0;
					$standing[$name]->score_after=0;
					$standing[$name]->time=0;
					$standing[$name]->user_name=$name;
				}
				$result_user=&$standing[$name];
				if($contest->rule==2){//ACM mode
					if($result_user->result[$submission->problem_id]->score_after==1)continue;
					$result_problem=&$result_user->result[$submission->problem_id];
					if($submission->result=="Accepted"){
						$result_problem->score_after=1;
						$result_problem->id_after=$submission->id;
						$standing[$name]->score_after+=1;
						if($submission->created_at<=$contest->end_time){
							$standing[$name]->score+=1;
							$result_problem->score=1;
							$result_problem->id=$submission->id;
							$result_problem->time=strtotime($submission->created_at)-strtotime($contest->begin_time);
							$standing[$name]->time+=$result_problem->time+1200*$result_problem->try;
							if(!$fb[$submission->problem_id]){
								$result_problem->fb=1;
								$fb[$submission->problem_id]=1;
							}
						}
					}
					else
						if($submission->created_at<=$contest->end_time){
							++$result_problem->try;
						}
				}
				else{
					if($contest->rule==0){//OI rule
						$result_problem=&$result_user->result[$submission->problem_id];
						if($submission->created_at<=$contest->end_time){
							$sk[$result_problem->id]=1;
							$result_user->score+=$submission->score-$result_problem->score;
							$result_problem->score=$submission->score;
							$result_problem->id=$submission->id;
							$result_problem->time=$result_user->time=strtotime($submission->created_at)-strtotime($contest->begin_time);
						}
						if(!$result_problem->id_after || $submission->score>$result_problem->score_after){
							$result_user->score_after+=$submission->score-$result_problem->score_after;
							$result_problem->score_after=$submission->score;
							$result_problem->id_after=$submission->id;
						}	
					}
					else{//IOI?
						$result_problem=&$result_user->result[$submission->problem_id];
						if($submission->created_at<=$contest->end_time){
							if(!$result_problem->id|| $submission->score>$result_problem->score){
								$result_user->score=$result_user->score_after+=$submission->score-$result_problem->score;
								$result_problem->score=$result_problem->score_after=$submission->score;
								$result_problem->id=$result_problem->id_after=$submission->id;
								$result_problem->time=$result_user->time=strtotime($submission->created_at)-strtotime($contest->begin_time);
								if($submission->result=="Accepted"){
									if(!$fb[$submission->problem_id]){
										$result_problem->fb=1;
										$fb[$submission->problem_id]=1;
									}
								}
							}	
						}else{
							if(!$result_problem->id_after||$submission->score>$result_problem->score_after){
								$result_user->score_after+=$submission->score-$result_problem->score_after;
								$result_problem->score_after=$submission->score;
								$result_problem->id_after=$submission->id;
							}	
						}
					}
				}
			}
			if($contest->rule==0){
				foreach($data as $submission){
					if(!in_array($submission->problem_id,$contest->problemset))continue;
					$name=$submission->user_name;
					$result_user=&$standing[$name];

					if($submission->created_at<=$contest->end_time && !array_key_exists($submission->id,$sk)){
						$result_problem=&$result_user->result[$submission->problem_id];
						if($submission->result=="Accepted"){
							if(!$fb[$submission->problem_id]){
								$result_problem->fb=1;
								$fb[$submission->problem_id]=1;
							}
						}
					}
				}
			}
			$standings=array();

			foreach($standing as $name=>$result){
				$standings[]=$result;

			}
	
			$cmp = function($a, $b) {
				return $a -> score < $b -> score||$a->score==$b->score && $a->time > $b->time;
			};
			
			usort($standings, $cmp);
			$last=null;
			$rank=0;
			$lastrank=0;
			foreach($standings as &$user){
				$rank++;
				if(!$last||($contest->rule==2&& $cmp($user,$last))||($contest->rule!=2 && $user->score<$last->score))$lastrank=$rank;
				$user->rank=$lastrank;
				$last=$user;
			}
			return view('contest.standings', ['standings' => $standings, 'contest' => $contest,'mode'=>$contest->rule]);

		} else {
			return redirect('404');
		}
	}
}
