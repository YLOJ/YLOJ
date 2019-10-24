<!DOCTYPE html>
@extends('layouts.app')


@section('content')
<?php
	if($rule==0){
		$sub->judge_info='';
		if($rule==0){$sub->result='Unshown';$sub->score=$sub->time_used=$sub->memory_used=-1;$sub->judge_info="";}
	}
?>
@if($sub->result !="Unshown")
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
@endif
        <table class="mdui-table"> 
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
						@if($sub->judge_info!='' && $rule!=2)
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
								@if($info[0] == 'Accepted') <div class="table-success text-success case-table" style="width:100%;" onClick="updatehide({{$subid}},{{$caseid}})" >
		  
								@elseif($info[0] == 'Partially Correct') <div class="table-warning case-table" style="color:orange;width:100%" onClick="updatehide({{$subid}},{{$caseid}})" > 
								@else <div class="table-danger text-danger case-table" style="width:100%" onClick="updatehide({{$subid}},{{$caseid}})" >
								@endif
								<div class="text-summary case-td" style="width:17%"> Case {{ $loop -> index}}: </div> 
								<div class="text-summary case-td" style="width:23%"> {{ $info[0] }} </div> 
								<div class="text-summary case-td" style="width:20%"> Score : {{ $info[5] }} </div>
								<div class="text-summary case-td" style="width:18%"> Time : {{ $info[1] }} ms </div> 
								<div class="text-summary case-td" style="width:22%"> Memory : {{ $info[2] }}kb  </div>
								</div>
								@if($rule==-1)
									<div class="text-detail" style="display:none" id="{{$subid}}-{{$caseid}}details">
									测试信息：
									<pre><code>{{$info[4]}}</code></pre>	
									</div>
								@endif
									<div style="height:1px"></div>
							@endforeach
						  @endcomponent
						@endforeach
								<script>
								function updatehide(subid,caseid){
									s=subid+'-'+caseid+'details';
		
									$('#'+s).toggle();
								}
								</script>
		
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
@endsection
