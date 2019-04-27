<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
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

        if (Auth::check() && Auth::User()->permission > 0) {

        } else if (NOW() < $contest->begin_time) {
            return redirect('404');
        }

        return view('contest.show', ['contest' => $contest]);
    }
}
