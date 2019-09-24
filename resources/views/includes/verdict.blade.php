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

  <a href="/contest/{{$sub->contest_id}}/problem/{{ $sub -> problem_id }}"> #{{ $sub -> problem_id }} : {{ $sub -> problem_name }} </a>
 @else
  <a href="/problem/{{ $sub -> problem_id }}"> #{{ $sub -> problem_id }} : {{ $sub -> problem_name }} </a>
 @endif
</td>
<td> 
  {{ $sub -> user_name }} 
</td>
<td id="result">
  @if ($sub -> result == "Waiting") 
    <a class="text-primary" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Accepted") 
    <a class="text-success" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Data Error") 
    <a style="color:#2F4F4F" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Judgement Failed") 
	<a style="color:#2F4F4F" href={{ $sub -> url }}>
  @elseif ($sub -> result == "Compile Error") 
    <a style="color:#696969" href={{ $sub -> url }}>
  @elseif (substr($sub->result,0,7)=="Running")
    <a style="color:#0033CC" href={{ $sub -> url }}>
  @else
    <a class="text-danger" href={{ $sub -> url }}>
  @endif
  <b> {{ $sub -> result }} </b> </a>
</td>
<td id="score">
  @if ($sub -> result == "Waiting" || $sub -> score == -1)
    <a class="text-primary" href={{$sub -> url}}> <b>/</b> </a>
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

<td> {{ $sub -> created_at }} </td>
