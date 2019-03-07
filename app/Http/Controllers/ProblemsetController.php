<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ProblemFormRequest;
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

    public function edit($id)
    {
        $problem = DB::table('problemset') -> where('id', $id) -> first();
        $title = $problem -> title;
        $content_md = $problem -> content_md;
        return view('problemset.edit', ['title' => $title, 'content_md' => $content_md, 'id' => $id]);
    }

    public function add_submit(ProblemFormRequest $request) 
    { 
        $title = $request -> input('title');
        $content_md = $request -> input('content_md');
        $flag = DB::insert('insert into `problemset` (`title`, `content_md`) values (?, ?)', [$title, $content_md]);
        return redirect('problemset');
    }

    public function edit_submit(ProblemFormRequest $request, $id)
    {
        $title = $request -> input('title');
        $content_md = $request -> input('content_md');
        $flag = DB::update('update `problemset` set `title` = ? `content_md` = ? where `id` = ?', [$title, $content_md, $id]);
        return redirect('problemset');
    }
    
    public function showProblem($id)
    {
        $markdowner = new Markdowner();
        $problem = DB::table('problemset') -> where('id', $id) -> first();
        $title = $problem -> title;
        $content_html = $markdowner -> toHTML($problem -> content_md);
        return view('problemset.show', ['title' => $title, 'content_html' => $content_html, 'id' => $id]);
    }
}