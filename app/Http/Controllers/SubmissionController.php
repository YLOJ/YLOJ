<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class SubmissionController extends Controller
{
    public function check($sub, $request, $para, $operator = '=') 
    {
        if($request->has($para) && $request->input($para) != null) {
            return $sub->where($para, $operator, $request->input($para));
        }

        return $sub;
    }

    public function index(Request $request)
    {
        $submissionset = DB::table('submission')->orderby('id', 'desc');

        $submission = $this->check($submission, $request, 'problem_id');
        $submission = $this->check($submission, $request, 'user_name');
        $submission = $this->check($submission, $request, 'min_score', '>=');
        $submission = $this->check($submission, $request, 'max_score', '<=');

        return view('submission.list', ['submissionset' => $submissionset -> paginate('20')]);
    }

	public function statistics($id)
    {
        $submissionset = DB::table('submission');
        $submissionset = $submissionset->orderby('time_used', 'asc');

        $data = $submissionset->get()->toArray();
        $map = array();

        foreach ($data as $sub) {
            if (!isset($map[$sub->user_id]) && $sub->problem_id == $id && $sub->score == 100) {
                $map[$sub->user_id] = 1;
                $submissionset = $submissionset -> where('id', '=', $sub->id, 'or');
            }
        }

        return view('problemset.statistics', ['submissionset' => $submissionset->paginate('10')]);
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
