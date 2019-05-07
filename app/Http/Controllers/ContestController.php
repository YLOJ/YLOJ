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
        return view('contest.list');
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
		$set = explode(',', $request -> input('problemset'));
		$map = array();
		$newset = array();
		foreach ($set as $id) {
			if (DB::table('problemset') -> where('id', $id) -> count() > 0 && !isset($map[$id])) {
				$map[$id] = 1;
				array_push($newset, $id);
			}
		}

        DB::insert('insert into `contest` (
            `title`,
            `contest_info`,
            `begin_time`,
			`end_time`,
			`problemset`,
			`rule`
        ) values (?, ?, ?, ?, ?, ?)', [
            $request -> input('title'),
            $request -> input('contest_info'),
            $request -> input('begin_time'),
			$request -> input('end_time'),
			implode(',', $newset),
			$request -> input('rule')
        ]);

        return redirect(route('contest.index'));
    }

	public function edit($id) 
    {
        if (Auth::check() && Auth::user()->permission > 0) {
			$contest = DB::table('contest') -> where('id', $id) -> first();
			return view('contest.edit', ['contest' => $contest]);
        } else {
            return redirect('404');
        }
    }

	public function edit_submit(ContestFormRequest $request, $cid) 
	{
		$set = explode(',', $request -> input('problemset'));
		$map = array();
		$newset = array();
		foreach ($set as $id) {
			if (DB::table('problemset') -> where('id', $id) -> count() > 0 && !isset($map[$id])) {
				$map[$id] = 1;
				array_push($newset, $id);
			}
		}

		DB::update("update `contest` set
            `title` = ?,
            `contest_info` = ?,
            `begin_time` = ?,
			`end_time` = ?,
			`problemset` = ?,
			`rule` = ?
			where `id` = ?", 
			[
				$request -> input('title'),
				$request -> input('contest_info'),
				$request -> input('begin_time'),
				$request -> input('end_time'),
				implode(',', $newset),
				$request -> input('rule'),
				$cid
			]
		);

        return redirect(route('contest.index'));
    }

}
