<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissionset = DB::table('submission') -> orderby('id', 'desc');

        if ($request -> has('problem_id') && $request -> input('problem_id') != null) {
            $submissionset = $submissionset -> where('problem_id', $request -> input('problem_id'));
        }
        if ($request -> has('user_name') && $request -> input('user_name') != null) {
            $submissionset = $submissionset -> where('user_name', $request -> input('user_name'));
        }
        if ($request -> has('min_score') && $request -> input('min_score') != null) {
            $submissionset = $submissionset -> where('score', '>=', $request -> input('min_score'));
        }
        if ($request -> has('max_score') && $request -> input('max_score') != null) {
            $submissionset = $submissionset -> where('score', '<=', $request -> input('max_score'));
        }

        return view('submission.list', ['submissionset' => $submissionset -> paginate('20')]);
    }

	public function statistics($id)
	{
        $submissionset = DB::table('submission')->where('problem_id', $id);
        $submissionset = $submissionset->where('score', '=', 100);
        $submissionset = $submissionset->orderby('time_used', 'asc');
        return view('problemset.statistics', ['submissionset' => $submissionset->paginate('10')]);
	}

    public function show($id) 
    {
        $submission = DB::table('submission') -> where('id', $id) -> first();
        return view('submission.show', ['sub' => $submission]);
    }

	public function submitproblem($id) 
    {
        return view('submission.submit', ['id' => $id]);
    }

	public function customtests() 
    {
        return view('submission.customtests');
    }
}
