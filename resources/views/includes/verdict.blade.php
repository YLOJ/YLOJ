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
<a href="{{$sub->url}}">
<?php
$verdict_list=array("OK","Accepted","Wrong Answer","Time Limit Exceeded","Memory Limit Exceeded","Runtime Error","Presentation Error","Partially Correct","Skipped","Compile Error","Compiler Time Limit Exceeded","Spj Error","Judgement Failed","Data Error","Waiting","Compiling","Running","Submitted");
if($sub->result<=1)echo "<b class='text-success'>";
else if($sub->result<=8)echo "<b class='text-danger'>";
else if($sub->result<=11)echo "<b style='color:#696969'>";
else if($sub->result<=13)echo "<b style='color:#2F4F4F'>";
else if($sub->result==16)echo "<b style='color:#0033CC'>";
else echo "<b>";
echo $verdict_list[$sub->result];
if($sub->result==16){
	if(isset($sub->data_id) && $sub->data_id)echo " on Test ".$sub->data_id;
}
echo "</b>";
?>
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
