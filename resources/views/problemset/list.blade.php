<!DOCTYPE html>
@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">
        {{ $problemset -> links() }}
    </div>

    @auth
    @if ( Auth::user()->permission > 0 )
    <div>
        <form method="post" action="problem/add">
            @include('buttons.submit' , ['text' => 'Add Problem'])
            @csrf
        </form>
    </div> <br>
    @endif
    @endauth

<?php
$flag = Auth::check() && Auth::user() -> permission > 0;
$links = $problemset -> links();
if (!$flag) {
	$newset = array();
	foreach ($problemset as $problem) {
		if ($problem -> visibility == true)
			array_push($newset, $problem);
	}
	$problemset = $newset;
}
?>

    <div class="row">
        <div class="col">
			@component('includes.problem_table', ['problemset' => $problemset])
			@endcomponent 
        </div>
    </div>

    <div class="row justify-content-center">
        {{ $links }}
    </div>

</div>

@endsection
