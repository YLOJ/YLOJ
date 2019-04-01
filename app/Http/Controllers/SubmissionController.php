<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissionset = DB::table('submission') -> orderby('id', 'desc') -> paginate(20);
        return view('submission.list', ['submissionset' => $submissionset]);
    }

    public function show($id) 
    {
        $submission = DB::table('submission') -> where('id', $id) -> first();
        return view('submission.show', ['sub' => $submission]);
    }
}
