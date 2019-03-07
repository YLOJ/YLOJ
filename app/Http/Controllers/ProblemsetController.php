<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProblemsetController extends Controller
{
    public function index()
    {
        $problemset = DB::table('problemset') -> paginate(20);
        return view('problemset.list', ['problemset' => $problemset]);
    }

    public function add()
    {
        return view('problemset.add');
    }

    public function add_submit(Request $request) 
    {
        return redirect('problemset');
    }
    
    public function showProblem($id)
    {
        $markdowner = new Markdowner();
        $content = DB::table('problemset') -> where('id', $id) -> first();
        $title = $content -> title;
        $content_html = $markdowner -> toHTML($content -> content_md);
        return view('problemset.show', ['title' => $title, 'content_html' => $content_html, 'id' => $id]);
    }
}