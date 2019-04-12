<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ProblemFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ProblemsetController extends Controller
{
    public function index()
    {
        $problemset = DB::table('problemset')->paginate(20);
        return view('problemset.list', ['problemset' => $problemset]);
    }

    public function show($id)
    {
        $markdowner = new Markdowner();
        $problem = DB::table('problemset')->where('id', $id)->first();

        return view('problemset.show', [
            'id' => $id,
            'title' => $problem->title,
            'time_limit' => $problem->time_limit,
            'memory_limit' => $problem->memory_limit,
            'content_html' => $markdowner->toHTML($problem->content_md),
        ]);
    }

    public function add()
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            return view('problemset.add');
        } else {
            return redirect('404');
        }
    }

    public function add_submit(ProblemFormRequest $request)
    {
        DB::insert('insert into `problemset` (
            `title`, 
            `time_limit`, 
            `memory_limit`, 
            `content_md`
        ) values (?, ?, ?, ?)', [
            $request->input('title'),
            $request->input('time_limit'),
            $request->input('memory_limit'),
            $request->input('content_md'),
        ]);

        return redirect(route('problem.index'));
    }

    public function edit($id)
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            $problem = DB::table('problemset')->where('id', $id)->first();
            return view('problemset.edit', [
                'id' => $id,
                'title' => $problem->title,
                'time_limit' => $problem->time_limit,
                'memory_limit' => $problem->memory_limit,
                'content_md' => $problem->content_md,
            ]);
        } else {
            return redirect('404');
        }
    }

    public function edit_submit(ProblemFormRequest $request, $id)
    {
        DB::update(
            "update `problemset` set 
            `title` = ?, 
            `time_limit` = ?,
            `memory_limit` = ?,
            `content_md` = ? 
            where `id` = ?",
            [
                $request->input('title'),
                $request->input('time_limit'),
                $request->input('memory_limit'),
                $request->input('content_md'),
                $id,
            ]
        );

        return redirect(route('problem.edit', $id));
    }

    public function data($id)
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            return view('problemset.data', [ 'id' => $id, ]);
        } else {
            return redirect('404');
        }
    }

    public function data_submit(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            Storage::disk('problems')->put(
                $id . '/data.zip',
                file_get_contents( $request->file('data') )
            );
            return redirect(route('problem.data', $id));
        } else {
            return redirect('404');
        }
    }

	public function data_download($id)
	{
		if (Auth::check() && Auth::user()->permission > 0) {
            return Storage::disk('problems')->download("$id/data.zip","data_$id.zip");
        } else {
            return redirect('404');
        }
	}
}
