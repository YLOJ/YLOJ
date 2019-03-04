<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function form(Request $request) 
    {
        return view('request.form');
    }

    public function fileup(Request $request) 
    {
        if ($request->hasFile('picture')) {
            dd($request->file('picture'));
        }
    }
}
