<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProblemsetController extends Controller
{
	public function index()
	{
		$problemset = DB::table('problemset') -> paginate(20);
		return view('problemset.list', ['problemset' => $problemset]);
	}

	public function fetch() 
	{
		$problemset = DB::table('problemset') -> paginate(20);
		dd ($problemset);
	}

	public function showProblem($id)
	{
		return view('problemset.show');
	}
}
