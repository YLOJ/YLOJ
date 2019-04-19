<?php
if(DB::table('problemset')->where('id','=',$sub->problem_id)->first()->visibility == false && (!Auth::check()||Auth::user()->permission<=0)){
	$sub->result ="Waiting";
	$sub->time_used =-1;
	$sub->memory_used =-1;
	$sub->user_name ="???";
	$sub->problem_id="?";
	$sub->problem_name="???";
	$sub->created_at="2333-33-33 33:33:33";
}
?>
<?php 
	if ($sub -> result == "waiting")
		$sub -> result = "Waiting";
	if (!isset($sub -> url))
		$sub -> url = url("submission/".$sub -> id);
?>
<td> 
	{{ $sub -> id }} 
</td>
<td>
	<a href="/problem/{{ $sub -> problem_id }}"> #{{ $sub -> problem_id }} : {{ $sub -> problem_name }} </a>
</td>
<td> 
	{{ $sub -> user_name }} 
</td>
<td>
	@if ($sub -> result == "Waiting") 
		<a class="text-primary" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Accepted") 
		<a class="text-success" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Data Error") 
		<a style="color:#2F4F4F" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Judgement Failed") 
		<a style="color:#2F4F4F" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Runtime Error") 
		<a style="color:#FF8C00" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Compile Error") 
		<a style="color:#696969" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Time Limit Exceeded") 
		<a style="color:#8B008B" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Memory Limit Exceeded") 
		<a style="color:#8B4513" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Presentation Error") 
		<a style="color:#556B2F" href={{ $sub -> url }}>
	@elseif ($sub -> result == "Partially Correct") 
		<a style="color:#3CB371" href={{ $sub -> url }}>
	@else 
		<a class="text-danger" href={{ $sub -> url }}>
	@endif
		<b> {{ $sub -> result }} </b> </a>
</td>
<td>
	@if ($sub -> result == "Waiting")
		<a> / </a>
	@else
		@if ($sub -> score == 100) 
			<a class="text-success" href={{$sub -> url}}>
		@elseif ($sub -> score > 0) 
			<a style="color:orange;" href={{$sub -> url}}>
		@else <a class="text-danger" href={{ $sub -> url }}>
		@endif
		<b> {{ $sub -> score }} </b> </a>
	@endif
</td>

@if ($sub -> time_used >= 0)
	<td> {{ $sub -> time_used }}ms </td>
@else 
	<td> / </td>
@endif

@if ($sub -> memory_used >= 0)
	<td> {{ $sub -> memory_used }}kb </td>
@else 
	<td> / </td>
@endif

<td> {{ $sub -> created_at }} </td>
