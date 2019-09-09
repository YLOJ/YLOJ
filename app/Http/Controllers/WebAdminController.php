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
			return redirect('/webadmin');
		}
		return view("404");
    }
}
