<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProblemsetController extends Controller
{
<<<<<<< HEAD
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
=======
    public function index()
    {
        $problemset = DB::table('problemset') -> paginate(20);
        return view('problemset.list', ['problemset' => $problemset]);
    }
    
    public function showProblem($id)
    {
        $markdowner = new Markdowner();
        $content = DB::table('problemset') -> where('id', $id) -> first();
        $title = $content -> title;
        $content_html = $markdowner -> toHTML($content -> content_md);
        return view('problemset.show', ['title' => $title, 'content_html' => $content_html]);
    }
>>>>>>> be68b19877420a18281fb6c072808dbd4517dd47
}
