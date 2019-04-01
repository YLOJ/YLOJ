<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index()
    {
        $submission = DB::table('submission') -> orderby('id') -> paginate(20);
        return view('submission.list', ['submission' => $submission]);
    }
}
