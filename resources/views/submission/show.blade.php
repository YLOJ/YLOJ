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
				@include('includes.verdict_table')
                <tbody>
                    <tr>
						@include('includes.verdict', ['sub' => $sub])
					</tr>
                </tbody>
            </table>

			@component('includes.collapse_box', ['id' => 'code', 'title' => 'Source Code'])
				<pre><code class="cpp">{{ $sub -> source_code }}</code></pre>
			@endcomponent
			@if($sub -> result == 'Compile Error')	
				@component('includes.collapse_box', ['id' => 'compile_info', 'title' => 'Compile Info'])
					<pre><code class="cpp">{{ $sub -> judge_info }}</code></pre>
				@endcomponent
			@elseif($sub -> result == 'Data Error')
				@component('includes.collapse_box', ['id' => 'error_info', 'title' => 'Error Details'])
					<pre><code class="cpp">{{ $sub -> judge_info }}</code></pre>
				@endcomponent
			@elseif($sub -> result != 'Waiting')
				<?php
				dd($sub);
				?>
				@component('includes.collapse_box', ['id' => 'details', 'title' => 'Details'])
					<ul class="list-group">
						@foreach($sub -> case_info as $info)
							@if($info['result'] == 'Accepted') <li class="list-group-item list-group-item-success"> 
							@elseif($info['result'] == 'Partially Correct') <li class="list-group-item list-group-item-warning"> 
							@else <li class="list-group-item list-group-item-danger">
							@endif
								Case {{ $loop -> index + 1 }} : {{ $info['result'] }} &nbsp &nbsp Score : {{ $info['score'] }} &nbsp &nbsp Time : {{ $info['time_used'] }} ms &nbsp &nbsp Memory : {{ $info['memory_used'] }}kb  
							</li>
						@endforeach
					</ul>
				@endcomponent
			@endif

			<br>
			@auth
				@if(Auth::user() -> permission > 0)
					@include('buttons.jump-danger', ['href' => url('submission/rejudge/'.$sub -> id), 'text' => 'Rejudge'])
					&nbsp
					@include('buttons.jump-danger', ['href' => url('submission/delete/'.$sub -> id), 'text' => 'Delete'])
				@endif
			@endauth

		</div>
	</div>
</div>

@endsection
