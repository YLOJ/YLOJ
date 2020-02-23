@extends('layouts.app')

@section('content')
	<?php
		$full_score=count($contest->problemset)*($contest->rule==2?1:100);
	?>
    <br>
    <h2 style='text-align:center;'> Standings </h2>
    <br>
	<input type="checkbox" name="showafter" id="showafter"><label for="showafter">显示改题分数</label>
	<div class="mdui-table-fluid mdui-hoverable">
    <table id="standings" class="mdui-table mdui-table-hoverable" style="layout:fixed">
      <thead>
        <tr>
          <th style="width: 5%;text-align:center">#</th>
          <th style="width: 15%;word-break:break-all">Username</th>
          <th style="width: 15%;word-break:break-all">Nickname</th>
		<?php
			$width=(int)(65/(count($contest->problemset)+1)).'%';
		?>
          <th  style="width:{{$width}}">Total Score</th>
          @foreach ($contest -> problemset as $problem)
			<th style="width:{{$width}}">
				<?php
					echo "<a href='/problem/".$problem."?contest_id=".$contest->id."'>".chr($loop -> index +65)."</a>";
				?>
			</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($standings as $user)

@if(!$user->in_contest)
	<tr class="after_contest">
@else
    <tr>
@endif
          <td style="text-align:center"> {{ $user -> rank}} </td>
		  <td style="word-break:break-all"> {{ $user -> user_name }} 
@if(!$user->in_contest)
*
@endif
</td>
          <td style="word-break:break-all"> {{ $user -> nickname}} </td>
		  <td>  
				@if($user->in_contest)
					<span class="in_contest">@include('includes.score',['score'=>$user->score,'score_full'=>$full_score])</span>
				@endif
				@if($user->score!=$user->score_after)
					<span class="after_contest">@include('includes.score',['score'=>$user->score_after,'score_full'=>$full_score,'text'=>'('.$user->score_after.')'])</span>
				@endif
			<?php
					echo $user->time?sprintf('<div class="submission_time">'."%d:%02d:%02d</div>", floor($user->time/ 3600), floor($user->time% 3600 / 60), $user->time%60):"";
			?>
		</td>

		  @foreach($user -> result as $sub)
			@if($sub->fb)
				<td style="background: rgb(244, 255, 245); ">
			@else
				<td>
			@endif
			@if($contest->rule!=2)
					@if($sub->id)				
						<a class="in_contest" href="/submission/{{$sub->id}}">
						@include('includes.score',['score'=>$sub->score])
						</a>
					@endif
					@if((!$sub->id || $sub->score!=$sub->score_after)&& $sub->id_after)				
						<a class="after_contest" href="/submission/{{$sub->id_after}}">
						@include('includes.score',['score'=>$sub->score_after,'text'=>'('.$sub->score_after.')'])
						</a>
					@endif
					<?php
					echo '<div class="submission_time">'.($sub->time?sprintf("%d:%02d:%02d", floor($sub->time/ 3600), floor($sub->time% 3600 / 60), $sub->time%60):"").'</div>';
					?>
			@else
					@if($sub->score||$sub->try)
	
						@if($sub->score)
							<a class="in_contest" href="/submission/{{$sub->id}}">@include("includes.score",['score'=>100,'text'=>"+".($sub->try?$sub->try:"")])</a>
						@else
							<span class="in_contest">@include("includes.score",['score'=>0,'text'=>"-".$sub->try])</span>
						@endif
					@endif

					@if(!$sub->score && $sub->score_after)				
						<a class="after_contest" href="/submission/{{$sub->id}}">@include("includes.score",['score'=>100,'text'=>"(+)"])</a>
					@endif
					@if($sub->score)
						<div class="submission_time"><?php
					echo ($sub->time?sprintf("%d:%02d:%02d", floor($sub->time/ 3600), floor($sub->time% 3600 / 60), $sub->time%60):"");
?></div>
					@endif
			@endif
			</td>
          @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
	</div>
	<script>
		if($("#showafter").prop("checked"))$(".after_contest").show();
		else $(".after_contest").hide();
		$("#showafter").change(function() {
			$(".after_contest").toggle();
		});
	</script>
@endsection
