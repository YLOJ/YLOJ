<!DOCTYPE html>
@extends('layouts.app')

<head>
  <link href="http://cdn.bootcss.com/highlight.js/8.0/styles/xcode.min.css" rel="stylesheet">
  <script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
  <script> hljs.initHighlightingOnLoad(); </script>
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

        <div class="accordion">
          @component('includes.collapse_box', ['id' => 'code', 'title' => 'Source Code'])
            <pre><code class="cpp">{{ $sub -> source_code }}</code></pre>
          @endcomponent

          @if($sub -> result == 'Compile Error')	
            @component('includes.collapse_box', ['id' => 'compile_info', 'title' => 'Compile Info'])
              <pre><code class="cpp">{{ $sub -> judge_info }}</code></pre>
            @endcomponent
          @elseif($sub -> result == 'Judgement Failed')
            @component('includes.collapse_box', ['id' => 'error_info', 'title' => 'Error Details'])
              <pre><code class="cpp">{{ $sub -> judge_info }}</code></pre>
            @endcomponent
          @elseif($sub -> result != 'Waiting' && $sub -> result != 'Running')
            @component('includes.collapse_box', ['id' => 'details', 'title' => 'Details'])

                @foreach($sub -> judge_info as $subtask)
                  @component('includes.collapse_box', 
                    ['id' => 'details'.($loop -> index + 1),
                    'title' => 'Subtask '.($loop -> index + 1).': '.$subtask[0][0].' ( Score = '.$subtask[0][1],' )'])

					<table class="table">
					  <tbody>
					@foreach($subtask as $info)
						@if($loop-> index == 0)
							@continue
						@endif

						@if($info[0] == 'Accepted') <tr class="table-success text-success"> 
						@elseif($info[0] == 'Partially Correct') <tr class="table-warning" style="color:orange"> 
						@else <tr class="table-danger text-danger">
						@endif
						<th style="width:17%"> Case {{ $loop -> index}}: </th> 
						<th style="width:23%"> {{ $info[0] }} </th> 
						<th style="width:20%"> Score : {{ $info[5] }} </th>
						<th style="width:18%"> Time : {{ $info[1] }} ms </th> 
						<th style="width:22%"> Memory : {{ $info[2] }}kb  </th>
						</tr>
					@endforeach
  					</tbody>
					</table>
				  @endcomponent
				@endforeach
			@endcomponent
		  @endif
		</div>

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
