<?php
  $prob=DB::table('problemset')->where('id','=',$sub->problem_id)->first();
  if (!isset($sub -> url))
  $sub -> url = url("submission/".$sub -> id);
?>

<td> 
  {{ $sub -> id }} 
</td>
<td>
 @if ($sub->contest_id)

  <a href="/problem/{{ $sub -> problem_id }}?contest_id={{$sub->contest_id}}"> #{{ $sub -> problem_id }} : {{ $sub -> problem_name }} </a>
 @else
  <a href="/problem/{{ $sub -> problem_id }}"> #{{ $sub -> problem_id }} : {{ $sub -> problem_name }} </a>
 @endif
</td>
<td> 
  {{ $sub -> user_name }} 
</td>
<td id="result">
  @if ($sub -> result == "Waiting") 
    <a href={{ $sub -> url }}>
  @elseif ($sub -> result == "Accepted") 
    <a style="color:#00cc00" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Data Error") 
    <a style="color:#2F4F4F" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Judgement Failed") 
	<a style="color:#2F4F4F" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Compile Error") 
    <a style="color:#696969" href={{ $sub -> url }}>
  @elseif (substr($sub->result,0,7)=="Running")
    <a style="color:#0033CC" href={{ $sub -> url }}>
  @else
    <a style="color:#cc0000" href={{ $sub -> url }}>
  @endif
  <b> {{ $sub -> result }} </b>
 	</a>
</td>
<td id="score">
	<a href="{{$sub->url}}">
		@if($sub->score==-1 || $sub->result=="Waiting")
			@include('includes.score',['score'=> $sub->score,'text'=>'/'])
		@else
			@include('includes.score',['score'=> $sub->score])
		@endif
	</a>
</td>
<td id="time">
@if ($sub -> time_used >= 0)
  {{ $sub -> time_used }}ms
@else 
  /
@endif
</td>
<td id="memory">
@if ($sub -> memory_used >= 0)
  {{ $sub -> memory_used }}kb
@else 
  /
@endif
</td>
<td id="length">
  <?php
  	if($sub->code_length<1000)
		echo $sub->code_length." b";
	else
		echo sprintf("%.2lf",$sub->code_length/1000)." kb";
	
 ?> 
</td>

<td> {{ $sub -> created_at }} </td>
