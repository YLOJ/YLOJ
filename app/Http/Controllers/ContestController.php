<?php

namespace App\Http\Controllers;

use App\Services\Markdowner;
use App\Http\Requests\ContestFormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
class ContestController extends Controller
{

	public function check($sub, $request, $para, $_para = "", $operator = '=') 
	{
		if ($_para == "") {
			$_para = $para;
		}
		if ($request->has($para) && $request->input($para) != null) {
			return $sub->where($_para, $operator, $request->input($para));
		}
		return $sub;
	}
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
		if (Storage::disk('data')->exists($pid.'/config.yml')){
			$config=Yaml::parse(Storage::disk('data')->get($pid.'/config.yml'));
			if(array_key_exists('time_limit',$config))$time_limit=$config['time_limit'];
			else $time_limit=1000;
			$time_limit.=' ms';

			if(array_key_exists('memory_limit',$config))$memory_limit=$config['memory_limit'];
			else $memory_limit=256000;
			$memory_limit.=' KB';

			if(array_key_exists('input_file',$config))$input_file=$config['input_file'];
			else $input_file='Standard Input';

			if(array_key_exists('output_file',$config))$output_file=$config['output_file'];
			else $output_file='Standard Output';
		}else
			$time_limit=$memory_limit=$input_file=$output_file="data not found!";
		return view('contest.showproblem', [
			'pid' => $pid,
			'title' => '['.$contest->title.'] '.$problem->title,
			'time_limit' => $time_limit,
			'memory_limit' => $memory_limit,
			'input_file' => $input_file,
			'output_file' => $output_file,
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
		if (Auth::user()->permission <= 0) {
			if (NOW() < $contest->begin_time || NOW() > $contest->end_time) {
				return redirect('404');
			}
		}

		$title = DB::table('problemset')->where('id',$pid)->first()->title;
		return view('contest.submitpage', ['pid' => $pid,'title' => $title,'cid' => $cid]);
	}

	public function submitcode(Request $request, $cid, $pid) 
	{

        if (!Auth::check()) {
            return redirect('login');
        }
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
		$xid=DB::getPdo()->lastInsertId();
		Redis::rpush('submission','test '.$xid);
		return redirect('/contest/mysubmission/'.$cid);
	}

	public function submission(Request $request, $id)
	{
		$contest = DB::table('contest')->where('id', $id)->first();
		if ((Auth::check() && Auth::user() -> permission > 0 )||(NOW()>$contest->end_time)) {
			$submission = DB::table('submission')->orderby('id', 'desc')->where('contest_id','=',$id);
			$submission = $this->check($submission, $request, 'problem_id');
			$submission = $this->check($submission, $request, 'user_name');
			$submission = $this->check($submission, $request, 'min_score', 'score', '>=');
			$submission = $this->check($submission, $request, 'max_score', 'score', '<=');
			return view('contest.submission', ['submissionset' => $submission -> paginate('10')]);
		} else {
			return redirect('404');
		}
	}
	public function mysubmission(Request $request, $id)
	{
		if (!Auth::check()) {
			return redirect('login');
		}
		$submission = DB::table('submission')->orderby('id', 'desc')->where('contest_id','=',$id)->where('user_name','=',Auth::User()->name);
		$contest = DB::table('contest')->where('id', $id)->first();
		if ($contest->rule==0 && ($contest->begin_time<=NOW() && NOW()<=$contest->end_time)) {
			return view('contest.mysubmission', ['submissionset' => $submission -> paginate('10'),'BAN'=>1]);
		}
		else return view('contest.mysubmission', ['submissionset' => $submission -> paginate('10'),'BAN'=>0]);
	}

	public function standings($cid)
	{
		$contest = DB::table('contest')->where('id', $cid)->first();
		if ((Auth::check() && Auth::user() -> permission > 0 )||(NOW()>$contest->end_time)) {
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

		} else {
			return redirect('404');
		}
	}
}
