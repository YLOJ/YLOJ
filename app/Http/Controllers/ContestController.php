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

        if (Auth::check() && Auth::user()->permission > 0) {

        } else if (NOW() < $contest->begin_time) {
            return redirect('404');
        }

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
}
