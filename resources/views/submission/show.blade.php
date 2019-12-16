<!DOCTYPE html>
@extends('layouts.app')


@section('content')
<?php
	if($rule==0){
		$sub->judge_info='';
		if($rule==0){$sub->result='Unshown';$sub->score=$sub->time_used=$sub->memory_used=-1;$sub->judge_info="";}
	}
?>
        <table class="mdui-table mdui-table-hoverable mdui-hoverable score-table"> 
          @include('includes.verdict_table')
          <tbody>
            <tr id="sub{{$sub->id}}">
              @include('includes.verdict', ['sub' => $sub])
            </tr>
          </tbody>
        </table>
          @component('includes.collapse_box', ['id' => 'code', 'title' => 'Source Code','main'=>1])
            <pre><code class="language-cpp">{{ $sub -> source_code }}</code></pre>
          @endcomponent
	          @if($sub -> result == 'Compile Error')	
	            @component('includes.collapse_box', ['id' => 'compile_info', 'title' => 'Compile Info'])
	              <pre><code>{{ $sub -> judge_info }}</code></pre>
	            @endcomponent
	          @elseif($sub -> result == 'Accepted' || $sub -> result == 'Unaccepted')
		            @component('includes.collapse_box', ['id' => 'details', 'title' => 'Details','main'=>1])
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
								@if($info[0] == 'Accepted') <div class="table-success text-success case-table" style="width:100%" onClick="updatehide({{$subid}},{{$caseid}})" >
		  
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

		<br>
		@auth
		  @if($permission)
			@include('buttons.jump-col', ['href' => url('submission/rejudge/'.$sub -> id), 'text' => 'Rejudge'])
			&nbsp
			@include('buttons.jump-col', ['href' => url('submission/delete/'.$sub -> id), 'text' => 'Delete'])
		  @endif
		@endauth
@endsection
