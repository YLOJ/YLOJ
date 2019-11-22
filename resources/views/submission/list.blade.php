
<!DOCTYPE html>
@extends('layouts.app')

@section('content')
  <?php
    if (isset($_GET['problem_id']))
      $problem_id = $_GET['problem_id'];
    else $problem_id = "";

    if (isset($_GET['user_name']))
      $user_name = $_GET['user_name'];
    else $user_name = "";

    if (isset($_GET['min_score']))
      $min_score = $_GET['min_score'];
    else $min_score = "";

    if (isset($_GET['max_score']))
      $max_score = $_GET['max_score'];
    else $max_score = "";
  ?>

	<style>
		.mdui-textfield {
			padding-bottom:0 !important;
		}
	</style>
	<div style="width:100%">
    	<form action="" method="get">
			<div class="mdui-textfield mdui-textfield-floating-label inline">
				<label class="mdui-textfield-label">Problem ID: </label>
				<input class="mdui-textfield-input" type="text" name="problem_id" value="{{$problem_id}}" style="height:2em;width:10em">
			</div>
&nbsp&nbsp 
			<div class="mdui-textfield mdui-textfield-floating-label inline">
				<label class="mdui-textfield-label">User Name: </label>
				<input class="mdui-textfield-input" type="text" name="user_name" value="{{$user_name}}" style="height:2em;width:10em">
			</div>
&nbsp&nbsp 
			<div class="mdui-textfield mdui-textfield-floating-label inline">
			  <label class="mdui-textfield-label">Score: </label>
			  <input class="mdui-textfield-input inline" type="text" name="min_score" value="{{$min_score}}" style="height:2em;width:4em;">
				~
			  <input class="mdui-textfield-input inline" type="text" name="max_score" value="{{$max_score}}" style="height:2em;width:4em;">
			</div>
&nbsp&nbsp 
			<button class="mdui-btn mdui-color-theme inline" type="submit">
			  <img src="{{ asset('svg/icons/search.ico') }}" class="icon-sm" />
			  Search
			</button>
			@if(Auth::check())
			<a class="mdui-btn mdui-color-theme inline" href={{url(url()->current().'?user_name='.Auth::User() -> name.'&problem_id='.$problem_id.'&min_score='.$min_score.'&max_score='.$max_score) }} style="float:right">
				My Submissions
			</a>
			@endif

       </form>
     </div>
    <table class="mdui-table mdui-table-hoverable mdui-hoverable score-table">
       @include('includes.verdict_table') 
       <tbody>
         @foreach ($submissionset as $sub)
             <tr id="sub{{$sub->id}}">
               @include('includes.verdict', ['sub' => $sub])
             </tr>
         @endforeach
       </tbody>
    </table>

	<div style="text-align: center">
      <?php 
        $str = $submissionset -> links();
        $arr = explode('?', $str);
        echo implode('?user_name='.$user_name.'&problem_id='.$problem_id.'&min_score='.$min_score.'&max_score='.$max_score.'&', $arr);
      ?>
    </div>
@endsection

