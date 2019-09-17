<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebAdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
		if($this->is_admin()){
			return view('webadmin.index');
		}
		return view("404");
    }

    public function permission(){
		if($this->is_admin()){
			return view('webadmin.permission');
		}
		return view("404");
    }
    public function update_permission(Request $request){
		if($this->is_admin()){
			$list=explode("\n",$request->userlist);
			foreach($list as $one){
				if(DB::select("select * from users where name=? and permission=?",[$one,$request->type])!=false){
					DB::update("update users set permission=? where name=?",[$request->type==1?0:1,$one]);
				}
			}
			return redirect('/webadmin/permission');
		}
		return view("404");
    }
	public function contest(){
		if($this->is_admin()){
			return view('webadmin.contest');
		}
		return view("404");
    }
    public function create_contest(Request $request){
		if($this->is_admin()){
			$list=explode("\n",$request->userlist);
			$task_num=$request->task_num;
			$rule=$request->rule;
			foreach($list as $one){
				if(DB::select("select * from users where name=?",[$one])){
					$cid=DB::table('contest')->insertGetId([
						'title'=>$one."'s contest",
						'contest_info'=>"",
						'begin_time'=>"2038-01-19 0:0:0",
						'end_time'=>"2038-01-19 3:14:07",
						'rule'=>$rule,
						'visibility'=>2
					]);
					DB::insert('insert into `contest_manager`(`contest_id`,`username`)values(?,?)',[
						$cid,$one
					]);
					for($i=1;$i<=$task_num;++$i){
						$pid=DB::table('problemset')->insertGetId([
							`title`=>$one."T".$i,
							`content_md`=>"",
							`visibility`=>2
						]);
						DB::insert('insert into `problem_manager`(`problem_id`,`username`)values(?,?)',[
							$pid,$one
						]);
						DB::insert('insert into `contest_problems`(`problem`,`id`)values(?,?)',[
							$pid,$cid
						]);
					}	
				}
			}
			return redirect('/webadmin/contest');
		}
		return view("404");
    }

}
