<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ProblemFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

use Chumper\Zipper\Zipper;

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

        if ($problem -> visibility == true || Auth::check() && Auth::user()->permission > 0) {
            return view('problemset.show', [
                'id' => $id,
                'title' => $problem->title,
                'time_limit' => $problem->time_limit,
                'memory_limit' => $problem->memory_limit,
                'content_html' => $markdowner->toHTML($problem->content_md),
            ]);
        } else {
            return redirect('404');
        }
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
                'visibility' => $problem->visibility
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
            `content_md` = ?,
            `visibility` = ?
            where `id` = ?",
            [
                $request->input('title'),
                $request->input('time_limit'),
                $request->input('memory_limit'),
                $request->input('content_md'),
                $request->input('visibility') != null,
                $id,
            ]
        );

        DB::update(
            "update `submission` set 
            `problem_name` = ? 
            where `problem_id` = ?",
            [
                $request->input('title'),
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
        if (Auth::check() && Auth::user() -> permission > 0) {
            Storage::deleteDirectory('data/'.$id);
            Storage::disk('data') -> put(
                $id . '/data.zip',
                file_get_contents( $request -> file('data') )
            );
            $zipper = new Zipper;
            $zipper -> make(storage_path('app/data/'.$id.'/data.zip')) -> extractTo(storage_path('app/data/'.$id.'/'));
            return redirect(route('problem.data', $id));
        } else {
            return redirect('404');
        }
    }

    public function data_download($id)
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            return Storage::disk('data')->download("$id/data.zip", "data_$id.zip");
        } else {
            return redirect('404');
        }
    }

    public function delete_problem($id)
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            DB::table('problemset') -> where('id', '=', $id) -> delete();
            DB::table('submission') -> where('problem_id', '=', $id) -> delete();
            Storage::disk('data') -> deleteDirectory($id);
        } else {
            return redirect('404');
        }
    }
}
