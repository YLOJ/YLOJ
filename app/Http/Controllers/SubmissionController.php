<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $submission = $this->check($submission, $request, 'problem_id');
        $submission = $this->check($submission, $request, 'user_name');
        $submission = $this->check($submission, $request, 'min_score', 'score', '>=');
        $submission = $this->check($submission, $request, 'max_score', 'score', '<=');

        return view('submission.list', ['submissionset' => $submission -> paginate('20')]);
    }

	public function statistics($id, Request $request)
	{
		$raw_data = DB::table('submission') -> where('score', '=', 100) -> where('problem_id', '=', $id);
        $raw_data = $raw_data -> orderby('time_used', 'asc') -> get() -> toArray();
		$map = array();
		$data = array();
        $count = 0;
        
		foreach ($raw_data as $sub) {
			if (!isset($map[$sub -> user_id])) {
				$map[$sub -> user_id] = 1;
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
        
		$title = DB::table('problemset')-> where('id','=',$id) -> first() -> title;
        return view('problemset.statistics', ['submissionset' => $data, 'id' => $id, 'title' => $title]);
	}

    public function show($id) 
    {
        $submission = DB::table('submission') -> where('id', $id) -> first();
        return view('submission.show', ['sub' => $submission]);
    }

	public function submitpage($id) 
    {
        return view('submission.submit', ['id' => $id]);
    }

    public function submitcode($id) 
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        dd($user);
    }

	public function customtests() 
    {
        return view('submission.customtests');
    }
}
