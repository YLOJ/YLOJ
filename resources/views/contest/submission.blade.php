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

  <div class="container">
    <p class="text-sm"> </p>
    <div class="row">
      <div class="col-md-10">
        <div class="hidden-xs">
          <form class="form-inline" action="/submission" method="get">
            <div class="form-group">
              <label class="control-label"> &nbsp Problem ID: &nbsp </label>
              <input class="form-control" type="text" name="problem_id" style="height:2em;width:4em" value={{$problem_id}}>
            </div>

            <div class="form-group">
              <label class="control-label"> &nbsp&nbsp User Name: &nbsp </label>
              <input class="form-control" type="text" name="user_name" style="height:2em;width:10em" value={{$user_name}}>
            </div>

            <div class="form-group">
              <label class="control-label"> &nbsp&nbsp Score: &nbsp </label>
              <input class="form-control" type="text" name="min_score" style="height:2em;width:4em" value={{$min_score}}>
              &nbsp ~ &nbsp
              <input class="form-control" type="text" name="max_score" style="height:2em;width:4em" value={{$max_score}}> &nbsp&nbsp&nbsp
            </div>
            @include('buttons.submit-icon' , ['icon' => 'search' , 'text' => 'Search'])
          </form>
        </div>
      </div>
      <div class="col-md-2" style="text-align:right">
        @auth
          @include('buttons.jump' , [ 
            'href' => url('/submission?user_name='.Auth::User() -> name.'&problem_id='.$problem_id.'&min_score='.$min_score.'&max_score='.$max_score) , 
            'text' => 'My Submissions'
            ])
          @endauth
      </div>
    </div>
    <p class="text-sm"> </p>

    <div class="row">
      <div class="col">
        <table class="table table-bordered">
          @include('includes.verdict_table') 
          <?php $count = 0; ?>
          <tbody>
            @foreach ($submissionset as $sub)
              @if ($count++ % 2 == 0)
                <tr style="background-color:#F3F3F3">
              @else
                <tr>
              @endif
              @include('includes.verdict', ['sub' => $sub])
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="row justify-content-center">
      <?php 
        $str = $submissionset -> links();
        $arr = explode('?', $str);
        echo implode('?user_name='.$user_name.'&problem_id='.$problem_id.'&min_score='.$min_score.'&max_score='.$max_score.'&', $arr);
      ?>
    </div>
  </div>
@endsection