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

            <pre>
                <code class="cpp">{{ $sub -> source_code }} </code>
            </pre>

			@auth
				@if(Auth::user() -> permission > 0)
					@include('buttons.jump-danger', ['href' => url('submission/rejudge/'.$sub -> id), 'text' => 'Rejudge'])
					&nbsp &nbsp
					@include('buttons.jump-danger', ['href' => url('submission/delete/'.$sub -> id), 'text' => 'Delete'])
				@endif
			@endauth

        </div>
    </div>
</div>

@endsection
