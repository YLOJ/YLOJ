<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

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

        return view('submission.list', ['submissionset' => $submission -> paginate('10')]);
    }

	public function statistics($id, Request $request)
	{
		if(DB::table('problemset')->where('id','=',$id)->first()->visibility==false){
			if(!Auth::check()||Auth::user()->permission<=0){
				return redirect('404');
			}
		}
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
		if(DB::table('problemset')->where('id','=',$sub->problem_id)->first()->visibility==false){
			if(!Auth::check()||Auth::user()->permission<=0){
				return redirect('404');
			}
		}
		$result_id = array(
			'Accepted', 
			'Wrong Answer', 
			'Time Limit Exceeded', 
			'Memory Limit Exceeded', 
			'Presentation Error',
			'Runtime Error',
			'Judgement Failed', 
			'Partially Correct');

		if ($sub -> result != 'Compile Error' && $sub -> result != 'Waiting') {
			$detail = explode(';', $sub -> judge_info);
			$task_id = 0;
			$sub -> task = array();
			foreach ($detail as $case) {
				$buffer = explode(',', $case);
				if (count($buffer) < 3) continue;
				$sub -> task[$task_id++] = array(
					'result' => $result_id[$buffer[0]],
					'time_used' => $buffer[1] < 0 ? '\\' : $buffer[1],
					'memory_used' => $buffer[2] < 0 ? '\\' : $buffer[2],
					'score' => $buffer[3]
				);
			}
		}	

        return view('submission.show', ['sub' => $sub]);
    }

	public function submitpage($id) 
    {
		$title = DB::table('problemset')-> where('id','=',$id) -> first() -> title;
        return view('submission.submit', ['id' => $id,'title' => $title]);
    }

    public function submitcode(Request $request, $id) 
    {
        if (!Auth::check()) {
            return redirect('login');
        }
		if (DB::table('problemset')->where('id','=',$id)->first()->visibility== false &&Auth::user()->permission<=0){
			return redirect('login');
		}

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

        return redirect('submission');
    }

	public function rejudge($id)
	{
		if (!Auth::check() || Auth::User() -> permission <= 0) {
			return redirect('404');
		}
		DB::table('submission') -> where('id', '=', $id) -> update(['result' => 'Waiting', 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		return redirect('submission/'.$id);
	}

	public function rejudge_problem($id)
	{
		if (!Auth::check() || Auth::User() -> permission <= 0) {
			return redirect('404');
		}
		DB::table('submission') -> where('problem_id', '=', $id) -> update(['result' => 'Waiting', 'score' => -1, 'time_used' => -1, 'memory_used' => -1]);
		return redirect('submission');
	}

	public function delete_submission($id)
	{
		if (!Auth::check() || Auth::User() -> permission <= 0) {
			return redirect('404');
		}
		DB::table('submission') -> where('id', '=', $id) -> delete();
		return redirect('submission');
	}

	public function delete_problem_submission($id)
	{
		if (!Auth::check() || Auth::User() -> permission <= 0) {
			return redirect('404');
		}
		DB::table('submission') -> where('problem_id', '=', $id) -> delete();
		return redirect('problem/edit/'.$id);
	}

	public function customtests() 
    {
        return view('submission.customtests');
	}
	
	public function customtests_submit(Request $req)
	{
		$id = DB::table('custom_test_submission')->insertGetId( ['judged' => false] );
		Storage::disk('customtest')->put( $id.'.cpp' , $req->input('source_code'));
		Storage::disk('customtest')->put( $id.'.in' , $req->input('input_file'));
		return view('submission.customtests', ['jid' => $id]);
	}
}
