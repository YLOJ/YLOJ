<!DOCTYPE html>
@extends('layouts.app')

<head>
    <link href="http://cdn.bootcss.com/highlight.js/8.0/styles/xcode.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
    <script>
        hljs.initHighlightingOnLoad();
    </script>
</head>

@section('content')

<div class="container">
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:12%">Submission ID</th>
                        <th style="width:25%">Problem Name</th>
                        <th style="width:10%">User</th>
                        <th style="width:9%">Result</th>
                        <th style="width:9%">Score</th>
                        <th style="width:9%">Time</th>
                        <th style="width:9%">Memory</th>
                        <th style="width:17%">Submission Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> {{ $sub -> id }} </td>
                        <td>
                            <a href="/problem/{{ $sub -> problem_id }}"> #{{ $sub -> problem_id }} :
                                {{ $sub -> problem_name }} </a>
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
                </tbody>
            </table>
            
            <pre>
                <code class="cpp">
{{ $sub -> source_code }}
                </code>
            </pre>

        </div>
    </div>
</div>

@endsection 