<!DOCTYPE html>
@extends('layouts.app')
<?php
if($BAN){
	foreach ($submissionset as $id => $sub){
		$submissionset[$id]->result='Unshown';
		$submissionset[$id]->score='Unshown';
		$submissionset[$id]->time_used='Unshown';
		$submissionset[$id]->memory_used='Unshown';
	}
}
?>
@section('content')
  <div class="container">
    <p class="text-sm"> </p>
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

  </div>
@endsection
