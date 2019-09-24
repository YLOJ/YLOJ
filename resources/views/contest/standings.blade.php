@extends('layouts.app')

@section('content')
  <div class="container">
    <br>
    <h2 style='text-align:center;'> Standings </h2>
    <br>
    <table id="standings" class="table table-bordered tablesort">
      <thead>
        <tr>
          <th style="width:5%">Rank</th>
          <th style="width:15%">User Name</th>
          @foreach ($contest -> problemset as $problem)
            <th><a href=/contest/{{$contest->id}}/problem/{{$problem->id}}> {{ $problem -> title }} </a></th>
          @endforeach
          <th style="width:20%">Total Score</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($standings as $user)
          @if ($loop -> index % 2 == 0) <tr style="background-color:#F3F3F3">
          @else <tr>
          @endif
          <td> {{ $user -> rank}} </td>
          <td> {{ $user -> user_name }} </td>
          @foreach($user -> result as $sub)
			@if($mode!=2)
	            @if($sub != null)
	              <td> 
	                @if($sub -> score == 100) <a class="text-success" href="{{ url('submission/'.$sub -> id) }}"> 
	                @elseif($sub -> score > 0) <a style="color:orange" href="{{ url('submission/'.$sub -> id) }}"> 
	                @else <a class="text-danger" href="{{ url('submission/'.$sub -> id) }}"> 
	                @endif
	                <b> {{ $sub -> score }} </b> 
	                </a> 
	              </td>
	            @else
	              <td class='text-danger'> 0 </td>
				@endif
			@else
	            @if($sub != null)
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
		              	echo '('.sprintf("%d:%02d:%02d", floor($sub->time/ 3600), floor($sub->time% 3600 / 60), $sub->time%60).')';
		            ?>
					</b> 
	                </a> 
	              </td>
	            @else
	              <td class='text-danger'> </td>
				@endif

			@endif

          @endforeach
		  <td class='text-primary'> <b> 
			<?php
				if($contest->rule==2)
					echo $user->score.'('.sprintf("%d:%02d:%02d", floor($user->time/ 3600), floor($user->time% 3600 / 60), $user->time%60).')';
				else 
					echo $user->score;
			?>
</b> </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
