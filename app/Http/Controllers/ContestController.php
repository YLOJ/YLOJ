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
    return view('contest.list', [
      'running_contests' => DB::table('contest') -> where('begin_time', '<=', now()) -> where('end_time', '>', now()) -> paginate(1000),
      'upcoming_contests' => DB::table('contest') -> where('begin_time', '>', now()) -> orderby('begin_time', 'asc') -> paginate(1000),
      'past_contests' => DB::table('contest') -> where('end_time', '<=', now()) -> orderby('end_time', 'desc') -> paginate(20)
    ]);
  }

  public function show($id) 
  {
    $contest = DB::table('contest')->where('id', $id)->first();
    if ($contest -> problemset != null) {
      $contest -> problemset = explode(',', $contest -> problemset);
      foreach ($contest -> problemset as &$problem) {
        $pid = $problem;
        $problem = (object)null;
        $problem -> id = $pid;
        $problem -> title = DB::table('problemset')->where('id', $pid)->first()->title;
      }
    } else {
      $contest -> problemset = array();
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
      DB::select('select * from problemset where id = ?',[$pid])[0] -> title,
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
    return redirect('submission');
  }

  public function standings($cid)
  {
    $contest = DB::table('contest') -> where('id', $cid) -> first();
    $data = DB::table('submission') -> where('contest_id', $cid) 
      -> where('created_at', '>=', $contest -> begin_time) -> where('created_at', '<=', $contest -> end_time);
    $standings = $data -> select('user_id') -> groupby('user_id') -> get() -> toarray();

    foreach ($standings as &$user) {
      $user -> result = array();
      $user -> user_name = DB::table('users') -> where('id', $user -> user_id) -> first() -> name;
      $user -> score = 0;
    }

    if ($contest -> problemset != null) {
      $contest -> problemset = explode(',', $contest -> problemset);
      foreach ($contest -> problemset as $pid) {
        foreach ($standings as &$user) {
          $data = DB::table('submission') -> where('contest_id', $cid) 
            -> where('created_at', '>=', $contest -> begin_time) -> where('created_at', '<=', $contest -> end_time);
          $data = $data -> where('problem_id', $pid);
          if ($contest -> rule == 0) // OI rule
            $data = $data -> orderby('created_at', 'desc');
          else 
            $data = $data -> orderby('score', 'desc') -> orderby('created_at', 'desc');
          $user -> result[$pid] = $data -> where('user_id', $user -> user_id) -> first();
          if ($user -> result[$pid] != null)
            $user -> score += $user -> result[$pid] -> score;
        }
      }

      foreach ($contest -> problemset as &$problem) {
        $pid = $problem;
        $problem = (object)null;
        $problem -> id = $pid;
        $problem -> title = DB::table('problemset')->where('id', $pid)->first()->title;
      }

      $cmp = function($a, $b) {
        return $a -> score < $b -> score;
      };
      usort($standings, $cmp);
    } else {
      $contest -> problemset = array();
    }

    return view('contest.standings', ['standings' => $standings, 'contest' => $contest]);
  }
}
