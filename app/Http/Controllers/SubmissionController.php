<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\Submission;
use App\Events\custom_test;
class SubmissionController extends Controller
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
    public function index(Request $request)
    {
		$submission = DB::table('submission')->orderby('id', 'desc');
        $submission = $this->check($submission, $request, 'problem_id');
        $submission = $this->check($submission, $request, 'user_name');
        $submission = $this->check($submission, $request, 'min_score', 'score', '>=');
        $submission = $this->check($submission, $request, 'max_score', 'score', '<=');

			//$standings = $data -> select('user_name') -> groupby('user_name') -> get() -> toarray();
		$contest=$this->contestEndedList();
		$submission = $submission->
			where(function ($query) use ($contest){
			    $query->where("contest_id",NULL)->whereIn('problem_id',$this->problemShowList())
			   ->orWhereIn('contest_id',$contest);
			});
        return view('submission.list', ['submissionset' => $submission -> paginate('10')]);
    }

    public function statistics($id, Request $request)
	{
		$cid=isset($request->contest_id)?$request->contest_id:NULL;
		if($cid){
			if(!in_array($id,$this->getProblemList($cid)))return redirect('404');
			if(
				!in_array($cid,$this->contestEndedList())
				&&!in_array($id,$this->problemManageList())
			)return redirect('404');
		}
		else{
			if(!in_array($id,$this->problemShowList()))
				return redirect('404');
		}

        $page = $request -> page ?: 1;
        $perPage = 10;
        $begin = ($page - 1) * $perPage;

        $raw_data = DB::table('submission') -> where('result', 1) -> where('problem_id', '=', $id);
        $fastest_data= $raw_data -> orderby('time_used', 'asc') -> get() -> toArray();
        $raw_data = DB::table('submission') -> where('result', 1) -> where('problem_id', '=', $id);
        $shortest_data= $raw_data -> orderby('code_length', 'asc') -> get() -> toArray();
        $map = array();
        $data = array();
        $count = 0;

        foreach ($fastest_data as $sub) {
            if (!isset($map[$sub -> user_id])) {
                $map[$sub -> user_id] = 1;
                $sub -> url = url('submission/'.$sub -> id);
                $sub -> id = ++$count;
                array_push($data, $sub);
            }
        }


        $data = new LengthAwarePaginator(
            array_slice($data, $begin, $perPage, true), 
            count($data), 
            $perPage,
            $page, 
            ['path' => $request -> url(), 'query' => $request -> query()]
		);
		$fastest=$data;
        $map = array();
        $data = array();
        $count = 0;

        foreach ($shortest_data as $sub) {
            if (!isset($map[$sub -> user_id])) {
                $map[$sub -> user_id] = 1;
                $sub -> url = url('submission/'.$sub -> id);
                $sub -> id = ++$count;
                array_push($data, $sub);
            }
        }


        $data = new LengthAwarePaginator(
            array_slice($data, $begin, $perPage, true), 
            count($data), 
            $perPage,
            $page, 
            ['path' => $request -> url(), 'query' => $request -> query()]
		);
		$shortest=$data;


        $title = DB::table('problemset') -> where('id','=',$id) -> first() -> title;
        return view('problemset.statistics', ['fastest' => $fastest,'shortest'=>$shortest,'id' => $id, 'title' => $title]);
    }

    public function show($id) 
    {
        $sub = DB::table('submission') -> where('id', $id) -> first();
		$rule= -1;
		$show_code=1;
		if($sub -> contest_id!=NULL){
			$contest=DB::table('contest')->where('id',$sub->contest_id)->first();
			$in_running_contest=$contest->begin_time<=NOW() && NOW()<=$contest->end_time;
		}
		else $in_running_contest=0;
		if (!in_array($sub->problem_id,$this->problemManageList())) {
			if($sub->contest_id!=NULL){
				if(!in_array($sub->contest_id,$this->contestShowList()))return redirect('404');
				if(!in_array($sub->contest_id,$this->contestManageList())&&NOW()<$contest->begin_time)return redirect('404');
				if($in_running_contest){
					$rule=$contest->rule;
					if($sub->user_id != Auth::user()->id)return redirect('404');
				}
			}
			else if(!in_array($sub->problem_id,$this->problemShowList()))
				return redirect('404');
			$permission=0;
        }
		else 
			$permission=1;
		if(!config("app.show_contest_submission_code",true)&&$in_running_contest && $sub->user_id != Auth::user()->id)$show_code=0;
		if($sub->result<=8)
			$sub->judge_info=json_decode($sub->judge_info);
		if($permission==1)
			$rule=-1;
        return view('submission.show', ['sub' => $sub,'permission'=>$permission,'rule'=>$rule,'show_code'=>$show_code]);
    }
	public function update(Request $request){
		$req=$request->all();
		if($req ['token'] != env('UPDATE_SUBMISSION_TOKEN'))return "kill";
		unset($req['token']);
		$tb=DB::table('submission') -> where('id', $req['id']);
		if(!$tb->exists())return "kill";
        $sub = $tb-> first();
		$tb->update(['result'=>$req['result'],
			'data_id'=>$req['data_id'],
			'score'=>$req['score'],
			'time_used'=>$req['time'],
			'memory_used'=>$req['memory'],
			'judge_info'=>$req['judge_info']
			]);
		if($sub->contest_id!=NULL){
        	$contest = DB::table('contest') -> where('id', $sub->contest_id) -> first();
			if($contest->rule==0 && now()<$contest->end_time)return "ok";
		}
		broadcast(new Submission($req));
		return "ok";
	}
    public function rejudge($id)
    {
		$pid=DB::table('submission')->where('id',$id)->first()->problem_id;
		if (!in_array($pid,$this->problemManageList())) {
            return redirect('404');
        }
        DB::table('submission') -> where('id', '=', $id) -> update(['result' => 14,'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		Redis::rpush('submission','test '.$id);
        return redirect('submission/'.$id);
    }
    public function rejudge_problem($id)
    {
		if (!in_array($id,$this->problemManageList()))
            return redirect('404');

		$sublist=DB::select('select * from submission where problem_id=?',[$id]);
        DB::table('submission') -> where('problem_id', '=', $id) -> update(['result' => 14, 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		foreach($sublist as $sub){
			Redis::rpush('submission','test '.$sub->id);
		}
        return redirect('submission');
    }
    public function rejudge_problem_ac($id)
    {
		if (!in_array($id,$this->problemManageList()))
            return redirect('404');

		$sublist=DB::select('select * from submission where problem_id=? and result=1',[$id]);
        DB::table('submission') -> where('problem_id', '=', $id) ->where('result','=',1) ->  update(['result' => 14, 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		foreach($sublist as $sub){
			Redis::rpush('submission','test '.$sub->id);
		}
        return redirect('submission');
    }
    public function delete_submission($id)
    {
		$pid=DB::table('submission')->where('id',$id)->first()->problem_id;
		if (!in_array($pid,$this->problemManageList()))
            return redirect('404');
        DB::table('submission') -> where('id', '=', $id) -> delete();
        return redirect('submission');
    }

    public function customtests() 
	{
		if(!Auth::check())return redirect("404");
		$test=DB::table('custom_tests')->where('username',Auth::user()->name)->orderby('id','desc')->get()->toArray();
		if($test){
			$test=$test[0];
			return view('submission.customtests',['id'=>$test->id, 'input'=>$test->input,'code'=>$test->code,'output'=>$test->output]);
		}
		else
		return view('submission.customtests',['id'=>-1, 'input'=>'','code'=>'','output'=>'']);
    }
    public function customtests_judge(Request $request) 
    {
		if(!Auth::check())return redirect('404');
		$test=DB::table('custom_tests')->where('username',Auth::user()->name)->orderby('id','desc')->get()->toArray();
		if($test && $test[0]->output=="Waiting..."){
			$test=$test[0];
			return view('submission.customtests',['id'=>$test->id, 'input'=>$request->input,'code'=>$request->code,'output'=>$test->output,'error'=>"last test hasn't done"]);
		}
		$xid=DB::table('custom_tests')->insertGetId(
			['code'=>$request->code,
			'input'=>$request->input,
			'output'=>"Waiting...",
			'username'=>Auth::user()->name]);
		Redis::rpush('submission','customtest '.$xid);
		return view('submission.customtests',['id'=>$xid, 'input'=>$request->input,'code'=>$request->code,'output'=>DB::table("custom_tests")->where('id',$xid)->get()->toArray()[0]->output]);
    }
	public function custom_test_update(Request $request){
		$req=$request->all();
		if($req ['token'] != env('UPDATE_SUBMISSION_TOKEN'))return redirect('404');
		unset($req['token']);
		broadcast(new custom_test($req));
	}
}
