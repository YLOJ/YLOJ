<!DOCTYPE html>
@extends('layouts.app')
<?php
if($BAN){
	foreach ($submissionset as $id => $sub){
		$submissionset[$id]->result=17;
		$submissionset[$id]->score="-1";
		$submissionset[$id]->time_used='-1';
		$submissionset[$id]->memory_used='-1';
	}
}
?>
@section('content')
	<p></p>
	<p></p>

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

    <div style="text-align:center">
      {{ $submissionset -> links() }}
	</div>
@endsection
