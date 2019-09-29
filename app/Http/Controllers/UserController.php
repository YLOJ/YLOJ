<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	public function profile(){
		if(!Auth::check())return redirect("login");
		return view('user.profile',['user'=>Auth::user()]);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
			'old_password' => ["yes"],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
			'nickname' => ['max:255'],
        ]);
    }

    public function update_profile(Request $request){
		if(!Auth::check())return redirect("login");
		$req=$request->all();
		$oldpass=$req['old-password'];
		$req['old-password']=Hash::check( Auth::user()->password , $req['old-password']);
		if(!$req['nickname'])$req['nickname']=Auth::user()->nickname;
		if(!$req['password']){
			$req['password']=$req['password_confirmation']=$oldpass;
		}
        $this->validator($req)->validate();
		DB::table("users")->where("name",Auth::user()->name)->update([
			'nickname'=>$req['nickname'],
			'password'=>Hash::make($req['password'])
		]);
		return redirect("user/profile");
    }

}
