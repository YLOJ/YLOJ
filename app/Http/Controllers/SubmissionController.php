<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
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
        if (!Auth::check() || Auth::User() -> permission <= 0) 
			$submission=$submission->where('contest_id','=',NULL);
        $submission = $this->check($submission, $request, 'problem_id');
        $submission = $this->check($submission, $request, 'user_name');
        $submission = $this->check($submission, $request, 'min_score', 'score', '>=');
        $submission = $this->check($submission, $request, 'max_score', 'score', '<=');
		$submission = $submission->whereIn('problem_id',$this->problemShowList());
        return view('submission.list', ['submissionset' => $submission -> paginate('10')]);
    }

    public function statistics($id, Request $request)
	{
		if(!in_array($id,$this->problemShowList()))return redirect('404');
        $raw_data = DB::table('submission') -> where('score', '=', 100) -> where('problem_id', '=', $id);
        $raw_data = $raw_data -> orderby('time_used', 'asc') -> get() -> toArray();
        $map = array();
        $data = array();
        $count = 0;

        foreach ($raw_data as $sub) {
            if (!isset($map[$sub -> user_id])) {
                $map[$sub -> user_id] = 1;
                $sub -> url = url('submission/'.$sub -> id);
                $sub -> id = ++$count;
                array_push($data, $sub);
            }
        }

        $page = $request -> page ?: 1;
        $perPage = 10;
        $begin = ($page - 1) * $perPage;

        $data = new LengthAwarePaginator(
            array_slice($data, $begin, $perPage, true), 
            count($data), 
            $perPage,
            $page, 
            ['path' => $request -> url(), 'query' => $request -> query()]
        );

        $title = DB::table('problemset') -> where('id','=',$id) -> first() -> title;
        return view('problemset.statistics', ['submissionset' => $data, 'id' => $id, 'title' => $title]);
    }

    public function show($id) 
    {
        $sub = DB::table('submission') -> where('id', $id) -> first();
		if (!in_array($sub->problem_id,$this->problemManageList())) {
			if($sub -> contest_id!=NULL){
				$contest=DB::table('contest')->where('id',$sub->contest_id)->first();
				if($contest->begin_time<=NOW() && NOW()<=$contest->end_time){
					if($sub->user_id != Auth::user()->id)return redirect('404');
					$sub->judge_info='';
					if($contest -> rule==0){$sub->result='Unshown';$sub->score=$sub->time_used=$sub->memory_used=-1;}
				}
			}
			else if (!in_array($sub->problem_id,$this->problemShowList()))
				return redirect('404');
			$permission=0;
        }
		else 
			$permission=1;
		if($sub->result=="Accepted" or $sub->result=="Unaccepted")
			$sub->judge_info=json_decode($sub->judge_info);
        return view('submission.show', ['sub' => $sub,'permission'=>$permission]);
    }
    public function submitpage($id) 
    {
        if (!Auth::check()) {
            return redirect('login');
        }
		if (!in_array($id,$this->problemShowList()))
            return redirect('404');
        $title = DB::table('problemset')-> where('id','=',$id) -> first() -> title;
        return view('submission.submit', ['id' => $id,'title' => $title]);
    }

    public function submitcode(Request $request, $id) 
    {

        if (!Auth::check()) {
            return redirect('login');
        }
		if (!in_array($id,$this->problemShowList()))
            return redirect('404');
        DB::insert('insert into submission (
            problem_id,
            problem_name,
            user_id,
            user_name,
            result,
            score,
            time_used,
            memory_used,
            source_code,
            created_at
        ) value(?,?,?,?,?,?,?,?,?,?) ',[
            $id,
            DB::select('select * from problemset where id=?',[$id])[0]->title,
            Auth::User()->id,
            Auth::User()->name,
            "Waiting",
            -1,
            -1,
            -1,
            $request->input('source_code'),
            NOW(),
    	    ]
	    );
		$xid=DB::getPdo()->lastInsertId();
		Redis::rpush('submission','test '.$xid);
        return redirect('submission');
    }

    public function rejudge($id)
    {
		$pid=DB::table('submission')->where('id',$id)->first()->problem_id;
		if (!in_array($pid,$this->problemManageList())) {
            return redirect('404');
        }
        DB::table('submission') -> where('id', '=', $id) -> update(['result' => 'Waiting', 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		Redis::rpush('submission','test '.$id);
        return redirect('submission/'.$id);
    }

    public function rejudge_problem($id)
    {
		if (!in_array($id,$this->problemManageList()))
            return redirect('404');

		$sublist=DB::select('select * from submission where problem_id=?',[$id]);
        DB::table('submission') -> where('problem_id', '=', $id) -> update(['result' => 'Waiting', 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		foreach($sublist as $sub){
			Redis::rpush('submission','test '.$sub->id);
		}
        return redirect('submission');
    }
    public function rejudge_problem_ac($id)
    {
		if (!in_array($id,$this->problemManageList()))
            return redirect('404');

		$sublist=DB::select('select * from submission where problem_id=? and result="Accepted"',[$id]);
        DB::table('submission') -> where('problem_id', '=', $id) ->where('result','=','Accepted') ->  update(['result' => 'Waiting', 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		foreach($sublist as $sub){
			Redis::rpush('submission','test '.$sub->id);
		}
        return redirect('submission');
    }
    public function delete_submission($id)
    {
		$pid=DB::table('submission')->where('id',$id)->first()->problem_id;
        if (!Auth::check() || Auth::User() -> permission <= 0) {
            return redirect('404');
        }
        DB::table('submission') -> where('id', '=', $id) -> delete();
        return redirect('submission');
    }

    public function delete_problem_submission($id)
    {

		if (!in_array($id,$this->problemManageList()))
            return redirect('404');
        DB::table('submission') -> where('problem_id', '=', $id) -> delete();
        return redirect('problem/edit/'.$id);
    }

    public function customtests() 
    {
        return view('submission.customtests');
    }
}
