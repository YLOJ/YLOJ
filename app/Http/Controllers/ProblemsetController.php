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
        $time_limit = $problem -> time_limit;
        $memory_limit = $problem -> memory_limit;
        $content_md = $problem -> content_md;

        return view('problemset.edit', [
            'id' => $id,
            'title' => $title, 
            'time_limit' => $time_limit,
            'memory_limit' => $memory_limit,
            'content_md' => $content_md,
            ]
        );
    }

    public function add_submit(ProblemFormRequest $request) 
    { 
        $title = $request -> input('title');
        $time_limit = $request -> input('time_limit');
        $memory_limit = $request -> input('memory_limit');
        $content_md = $request -> input('content_md');
        
        DB::insert('insert into `problemset` (
            `title`, 
            `time_limit`, 
            `memory_limit`, 
            `content_md`
            ) values (?, ?, ?, ?)', [
                $title, 
                $time_limit,
                $memory_limit,
                $content_md,
            ]
        );
        
        return redirect('problemset');
    }

    public function edit_submit(ProblemFormRequest $request, $id)
    {
        $title = $request -> input('title');
        $time_limit = $request -> input('time_limit');
        $memory_limit = $request -> input('memory_limit');
        $content_md = $request -> input('content_md');

        DB::update("update `problemset` set 
            `title` = ?, 
            `time_limit` = ?,
            `memory_limit` = ?,
            `content_md` = ? 
            where `id` = ?", [
                $title, 
                $time_limit,
                $memory_limit,
                $content_md, 
                $id,
            ]
        );

        return redirect('problemset');
    }
    
    public function showProblem($id)
    {
        $markdowner = new Markdowner();
        $problem = DB::table('problemset') -> where('id', $id) -> first();
        
        $title = $problem -> title;
        $time_limit = $problem -> time_limit;
        $memory_limit = $problem -> memory_limit;
        $content_html = $markdowner -> toHTML($problem -> content_md);
        
        return view('problemset.show', [
            'id' => $id,
            'title' => $title,
            'time_limit' => $time_limit,
            'memory_limit' => $memory_limit,
            'content_html' => $content_html, 
            ]
        );
    }
}