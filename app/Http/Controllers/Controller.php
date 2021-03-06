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
	public function is_admin(){
		return Auth::check() && Auth::user()->permission>1;
	}
	public function problemShowListSQL(){
		if(Auth::check()){
			if($this->is_admin())return DB::table('problemset'); 
			else{
				$managerlist=array_column(DB::select('select problem_id from problem_manager where username=?',[Auth::user()->name]),'problem_id');
				return DB::table('problemset')->whereIn('id',$managerlist)->orWhere('visibility','<=',Auth::user()->permission);
			}
			return DB::table('problemset')->where('visibility','<=',Auth::user()->permission);
		}
		return DB::table('problemset')->where('visibility','=',0);
	}

	public function problemShowList(){
		return array_column($this->problemShowListSQL()->get()->toArray(),'id');
	}
	public function problemManageList(){
		if(Auth::check()){
			if($this->is_admin())return array_column(DB::select('select id from problemset'),'id');
			else return array_column(DB::select('select problem_id from problem_manager where username=?',[Auth::user()->name]),'problem_id');	
		}
		else return array();		
	} 
	public function contestShowListSQL(){
		if(Auth::check()){
			if($this->is_admin())return DB::table('contest'); 
			else{
				$list=array_column(DB::select('select contest_id from contest_manager where username=?',[Auth::user()->name]),'contest_id');
				return DB::table('contest')->
					where(function ($query) use ($list){
					    $query->whereIn('id', $list)
					   ->orWhere("visibility","<=",Auth::user()->permission);
					});
			}
		}
		return DB::table('contest')->where('visibility','=',0);
	}

	public function contestShowList(){
		return array_column($this->contestShowListSQL()->get()->toArray(),'id');
	}
	public function contestEndedList(){
		return array_column($this->contestShowListSQL()->where("end_time","<=",now())->get()->toArray(),'id');
	}
	public function contestManageList(){
		if(Auth::check()){
			if($this->is_admin())return array_column(DB::select('select id from contest'),'id');

			else return array_column(DB::select('select contest_id from contest_manager where username=?',[Auth::user()->name]),'contest_id');	
		}
		return array();
	}
	public function getProblemList($id){
		$problems=array_column(DB::select('select problem from contest_problems where id=?',[$id]),'problem');
		return $problems;
	}
}
