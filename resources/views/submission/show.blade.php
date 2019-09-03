<!DOCTYPE html>
@extends('layouts.app')

<head>
  <link href="http://cdn.bootcss.com/highlight.js/8.0/styles/xcode.min.css" rel="stylesheet">
  <script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
  <script> hljs.initHighlightingOnLoad(); </script>
</head>

@section('content')
<script src=/js/app.js></script>
<script>
var style={};
style["Waiting"]='class="text-primary"';
style["Accepted"]='class="text-success"';
style["Data Error"]='style="color:#2F4F4F"';
style["Judgement Failed"]='style="color:#2F4F4F"';
style["Compile Error"]='style="color:#696969"';
var sub=@json($sub);
Echo.channel('Submission')
.listen('.submission.update', (e) => {
	xsub=e.message;
	if('result' in xsub){
		$('#sub'+xsub['id']+" #result").html([
			"<a "+
			(xsub['result'] in style?
				style[xsub['result']]:
				xsub['result'].substring(0,7)=="Running"?
				'style="color:#0033CC"':'class="text-danger"'
			)	+" href="+sub['url']+">"
		,
		"<b>"+xsub['result']+"</b>",
		"</a>"
	].join('\n'));
	}
	if('score' in xsub){
		$('#sub'+xsub['id']+" #score").html([
			"<a "+
			(	xsub['score']=='-1'?
				'class="text-primary"':
				xsub['score']=='100'?
				'class="text-success"':
				xsub['score']>'0'?
				'style="color:orange"':
				'class="text-danger"'
			)	+" href="+sub['url']+">"
		,
		"<b>"+(xsub['score']=='-1'?"/":xsub['score'])+"</b>",
		"</a>"
	].join('\n'));

	}
	if('time' in xsub){
		$('#sub'+xsub['id']+" #time").html(
			xsub['time']>=0?xsub['time']+'ms':'/'
		)
	}
	if('memory' in xsub){
		$('#sub'+xsub['id']+" #memory").html(
			xsub['memory']>=0?xsub['memory']+'kb':'/'
		)
	}
});
</script>
  <div class="container">
    <div class="row">
      <div class="col">
        <table class="table table-bordered"> 
          @include('includes.verdict_table')
          <tbody>
            <tr id="sub{{$sub->id}}">
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
						@if($info[0] == 'Accepted') <div class="table-success text-success" style="width:100%;" onClick="updatehide({{$subid}},{{$caseid}})" >
  
						@elseif($info[0] == 'Partially Correct') <div class="table-warning" style="color:orange;width:100%" onClick="updatehide({{$subid}},{{$caseid}})" > 
						@else <div class="table-danger text-danger" style="width:100%" onClick="updatehide({{$subid}},{{$caseid}})" >
						@endif
						<div class="text-summary" style="width:17%;float:left"> Case {{ $loop -> index}}: </div> 
						<div class="text-summary" style="width:23%;float:left"> {{ $info[0] }} </div> 
						<div class="text-summary" style="width:20%;float:left"> Score : {{ $info[5] }} </div>
						<div class="text-summary" style="width:18%;float:left"> Time : {{ $info[1] }} ms </div> 
						<div class="text-summary" style="width:22%;float:left"> Memory : {{ $info[2] }}kb  </div>
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
