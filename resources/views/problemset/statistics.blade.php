<!DOCTYPE html>
@extends('layouts.app')

@section('content')

<div class="container">

	<div style="text-align:center"> 
		<br> <br>
		<h2> Accepted Submissions </h2>	
		<br> <br>
	</div>

    <div class="row">
        <div class="col">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:7%">Rank</th>
                        <th style="width:25%">Problem Name</th>
                        <th style="width:10%">User</th>
                        <th style="width:14%">Result</th>
                        <th style="width:9%">Score</th>
                        <th style="width:9%">Time</th>
                        <th style="width:9%">Memory</th>
                        <th style="width:17%">Submission Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($submissionset as $sub)
                    @if ($sub -> id % 2 == 0)
                    <tr style="background-color:#F3F3F3">
                        @else
                    <tr>
                        @endif
                        <td> {{ $sub -> id }} </td>
                        <td>
                            <a href="/problem/{{ $sub -> problem_id }}"> #{{ $sub -> problem_id }} : {{ $sub -> problem_name }} </a>
                        </td>
                        <td> {{ $sub -> user_name }} </td>
                        <td>
                            @if ($sub -> result == "accepted") <a class="text-success">
                                @else <a class="text-danger">
                                    @endif
                                    <b> {{ $sub -> result }} </b> </a>
                        </td>
                        <td>
                            @if ($sub -> score == 100) <a class="text-success">
                                @elseif ($sub -> score > 0) <a class="text-warning">
                                    @else <a class="text-danger">
                                        @endif
                                        <b> <a href="/submission/{{$sub -> id}}"> {{ $sub -> score }} </a> </b> </a>
                        </td>
                        <td> {{ $sub -> time_used }}ms </td>
                        <td> {{ $sub -> memory_used }}kb </td>
                        <td> {{ $sub -> created_at }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-center">
        {{ $submissionset -> links() }}
    </div>

</div>

@endsection 
