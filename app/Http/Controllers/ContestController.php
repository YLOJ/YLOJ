<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ContestFormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContestController extends Controller
{
    public function index()
    {
        $contests = DB::table('contest')->paginate(20);
        return view('contest.list', ['contests' => $contests]);
    }

    public function show($id) 
    {
        $contest = DB::table('contest')->where('id', $id)->first();
        return view('contest.show', ['contest' => $contest]);
    }

    public function add() 
    {
        if (Auth::check() && Auth::user()->permission > 0) {
            return view('contest.add');
        } else {
            return redirect('404');
        }
    }

    public function add_submit(ContestFormRequest $request) 
    {
        DB::insert('insert into `contest` (
            `title`,
            `contest_info`,
            `begin_time`,
            `end_time`
        ) values (?, ?, ?, ?)', [
            $request->input('title'),
            $request->input('contest_info'),
            $request->input('begin_time'),
            $request->input('end_time'),
        ]);

        return redirect(route('contest.index'));
    }

    public function showproblem($cid, $pid) 
    {
        $markdowner = new Markdowner();
        $contest = DB::table('contest')->where('id', $cid)->first();
        $problem = DB::table('problemset')->where('id', $pid)->first();

        if (!Auth::check()) {
            return redirect('login');
        }
        if (NOW() < $contest->begin_time && Auth::user()->permission <= 0) {
            return redirect('404');
        }

        return view('contest.showproblem', [
            'pid' => $pid,
            'title' => '['.$contest->title.'] '.$problem->title,
            'time_limit' => $problem->time_limit,
            'memory_limit' => $problem->memory_limit,
            'content_html' => $markdowner->toHTML($problem->content_md),
            'cid' => $cid,
        ]);
    }

    public function submitpage($cid, $pid) 
    {
        $contest = DB::table('contest')->where('id', $cid)->first();

        if (!Auth::check()) {
            return redirect('login');
        }
        if (NOW() < $contest->begin_time && Auth::user()->permission <= 0) {
            return redirect('404');
        }

        $title = DB::table('problemset')->where('id',$pid)->first()->title;
        return view('contest.submitpage', ['pid' => $pid,'title' => $title,'cid' => $cid]);
    }

    public function submitcode(Request $request, $cid, $pid) 
    {
        DB::insert('insert into submission (
            problem_id,
            problem_name,
            user_id,
            user_name,
            result,
            score,
            time_used,
            memory_used,
            source_code,
            created_at,
            contest_id
        ) value(?,?,?,?,?,?,?,?,?,?,?)',[
            $pid,
            DB::select('select * from problemset where id=?',[$id])[0]->title,
            Auth::User()->id,
            Auth::User()->name,
            "Waiting",
            -1,
            -1,
            -1,
            $request->input('source_code'),
            NOW(),
            $cid,
        ]);
    }
}
