<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	public function problemShowListSQL(){
		if(Auth::check()){
			if(Auth::user()->permission>0)return DB::table('problemset'); 
			else{
				$managerlist=array_column(DB::select('select problem_id from problem_manager where username=?',[Auth::user()->name]),'problem_id');
				return DB::table('problemset')->whereIn('id',$managerlist)->orWhere('visibility','=','1');
			}
		}
		return DB::table('problemset')->where('visibility','=','1');
	}

	public function problemShowList(){
		return array_column($this->problemShowListSQL()->get()->toArray(),'id');
	}
	public function problemManageList(){
		if(Auth::check()){
			if(Auth::user()->permission>0)return array_column(DB::select('select id from problemset'),'id');
			else return array_column(DB::select('select problem_id from problem_manager where username=?',[Auth::user()->name]),'problem_id');	
		}
		else return array();		
	} 
}
