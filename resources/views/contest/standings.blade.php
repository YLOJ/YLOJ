@extends('layouts.app')

@section('content')
    <br>
    <h2 style='text-align:center;'> Standings </h2>
    <br>
@if($contest->rule!=2)
	<input type="checkbox" name="showafter" id="showafter"><label for="showafter">显示改题分数</label>
@endif
	<div class="mdui-table-fluid">
    <table id="standings" class="mdui-table mdui-table-hoverable" style="word-wrap:break-word;layout:fixed">
      <thead>
        <tr>
          <th style="width: 5%">Rank</th>
          <th style="width: 15%">Username</th>
          <th style="width: 15%">Nickname</th>
		<?php
			$width=(int)(55/count($contest->problemset)).'%';
		?>
          <th  style="width:{{$width}}">Total Score</th>
          @foreach ($contest -> problemset as $problem)
			<th style="width:{{$width}}">
				<?php
					echo "<a href='/contest/".$contest->id."/problem/".$problem->id."'>".chr($loop -> index +65)."</a>";
				?>
<!-- {{ $problem -> title }} -->
			</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($standings as $user)
          <tr>
          <td> {{ $user -> rank}} </td>
		  <td> {{ $user -> user_name }} 
@if(!$user->in_contest)
*
@endif
</td>
          <td> {{ $user -> nickname}} </td>
		  <td class='text-primary'> <b> 
			<?php
				if($contest->rule==2)
					echo $user->score.($user->score?'('.sprintf("%d:%02d:%02d", floor($user->time/ 3600), floor($user->time% 3600 / 60), $user->time%60).')':'');
				else 
					echo $user->score.'<span class="after">('.$user->score_after.')</span>';
			?>
</b> </td>

          @foreach($user -> result as $sub)
			@if($mode!=2)

	            <td> 
	            @if($sub->found!= null)
	                @if($sub -> score == 100) <a class="text-success" href="{{ url('submission/'.$sub -> id) }}"> 
	                @elseif($sub -> score > 0) <a style="color:orange" href="{{ url('submission/'.$sub -> id) }}"> 
	                @else <a class="text-danger" href="{{ url('submission/'.$sub -> id) }}"> 
	                @endif
	                <b> {{ $sub -> score }}</b> 
	                </a> 
	            @else
	              <b class="text-danger"> 0</b>
				@endif
				<span class="after">
	            @if($sub->after->found!= null)
	                @if($sub ->after-> score == 100) <a class="text-success" href="{{ url('submission/'.$sub -> after->id) }}"> 
	                @elseif($sub->after -> score > 0) <a style="color:orange" href="{{ url('submission/'.$sub->after -> id) }}"> 
	                @else <a class="text-danger" href="{{ url('submission/'.$sub -> after->id) }}"> 
	                @endif
	                <b>({{ $sub -> after->score }})</b> 
	                </a> 
	            @else
	              <b class="text-danger">(0)</b>
				@endif
				</span>
	              </td>
			@else
	            @if($sub->found ==1)
	              <td> 

	                @if($sub -> score==1) <a class="text-success" href="{{ url('submission/'.$sub -> id) }}"> 
	                @elseif($sub -> score==2) <a style="color:#0033CC" href="{{ url('submission/'.$sub -> id) }}"> 
	                @else <a class="text-danger" href="{{ url('submission/'.$sub -> id) }}"> 
	                @endif
					<b> 
		            <?php
						if($sub->score>0)echo '+';
						else echo '-';
						if($sub->try>0)echo $sub->try;	
		              	if($sub->score>0)echo '('.sprintf("%d:%02d:%02d", floor($sub->time/ 3600), floor($sub->time% 3600 / 60), $sub->time%60).')';
		            ?>
					</b> 
	                </a> 
	              </td>
	            @else
	              <td class='text-danger'> </td>
				@endif

			@endif

          @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
	</div>
	<script>
		if($("#showafter").prop("checked"))$(".after").show();
		else $(".after").hide();
		$("#showafter").change(function() {
			$(".after").toggle();
		});
	</script>
@endsection
