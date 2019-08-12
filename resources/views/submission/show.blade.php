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
              <pre><code>{{ $sub -> judge_info }}</code></pre>
            @endcomponent
          @elseif($sub -> result == 'Accepted' || $sub -> result == 'Unaccepted')
            @component('includes.collapse_box', ['id' => 'details', 'title' => 'Details'])
				@if($sub->judge_info!='')
                @foreach($sub -> judge_info as $subtask)
                  @component('includes.collapse_box', 
                    ['id' => 'details'.($loop -> index + 1),
                    'title' => 'Subtask '.($loop -> index + 1).': '.$subtask[0][0].' ( Score = '.$subtask[0][1],' )'])
					<?php
						$subid=$loop->index+1;
					?>
					@foreach($subtask as $info)
						@if($loop-> index == 0)
							@continue
						@endif
						<?php
							$caseid=$loop->index;
						?>
						@if($info[0] == 'Accepted') <div class="table-success text-success" style="width:100%" onClick="updatehide({{$subid}},{{$caseid}})" >
						@elseif($info[0] == 'Partially Correct') <div class="table-warning" style="color:orange;width:100%" onClick="updatehide({{$subid}},{{$caseid}})" > 
						@else <div class="table-danger text-danger" style="width:100%" onClick="updatehide({{$subid}},{{$caseid}})" >
						@endif
						<div>
						<div class="text-summary" style="width:17%"> Case {{ $loop -> index}}: </div> 
						<div class="text-summary" style="width:23%"> {{ $info[0] }} </div> 
						<div class="text-summary" style="width:20%"> Score : {{ $info[5] }} </div>
						<div class="text-summary" style="width:18%"> Time : {{ $info[1] }} ms </div> 
						<div class="text-summary" style="width:22%"> Memory : {{ $info[2] }}kb  </div>
						</div>
						</div>
						<div class="text-detail" style="display:none" id="{{$subid}}-{{$caseid}}details">
						测试信息：
						<pre><code>{{$info[4]}}</code></pre>	
						</div>
						<script>
						function updatehide(subid,caseid){
							s=subid+'-'+caseid+'details';

							$('#'+s).toggle();
						}
						</script>
					@endforeach
				  @endcomponent
				@endforeach
				@endif
			@endcomponent
          @elseif($sub -> result != 'Waiting' && $sub->result!='Running')
            @component('includes.collapse_box', ['id' => 'error_info', 'title' => 'Error Details'])
              <pre><code>{{ $sub -> judge_info }}</code></pre>
            @endcomponent
		  @endif
		</div>

		<br>
		@auth
		  @if($permission)
			@include('buttons.jump-danger', ['href' => url('submission/rejudge/'.$sub -> id), 'text' => 'Rejudge'])
			&nbsp
			@include('buttons.jump-danger', ['href' => url('submission/delete/'.$sub -> id), 'text' => 'Delete'])
		  @endif
		@endauth
	  </div>
	</div>
  </div>
@endsection
