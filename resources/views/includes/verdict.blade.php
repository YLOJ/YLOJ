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

@if ($sub -> result == "Accepted" || $sub -> result == "Wrong Answer")
	<td> {{ $sub -> time_used }}ms </td>
	<td> {{ $sub -> memory_used }}kb </td>
@else
	<td> / </td>
	<td> / </td>
@endif

<td> {{ $sub -> created_at }} </td>
