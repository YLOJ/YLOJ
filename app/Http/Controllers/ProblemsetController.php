<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProblemsetController extends Controller
{
    public function index()
    {
        return view('problemset.list');
    }

    public function showProblem($id)
    {
        return view('problemset.show', ['id' => $id]);
    }
}
