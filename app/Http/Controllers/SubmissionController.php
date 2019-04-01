<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show($id) 
    {
        $submission = DB::table('submission') -> where('id', $id) -> first();
        return view('submission.show', ['sub' => $submission]);
    }
}
