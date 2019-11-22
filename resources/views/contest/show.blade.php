@extends('layouts.app')

@section('content')
    <br>
    <h1 style="text-align:center"> {{ $contest -> title }} </h1>
    <br>

    <?php $id = $contest -> id ?>
    <div class="text-center">
	    <div class="mdui-btn-group">
	      @include('buttons.jump-icon' , ['href' => url('/contest/mysubmission/'.$id) , 'icon' => 'text-left' , 'text' => 'My Submissions'])
	      @include('buttons.jump-icon' , ['href' => url('/contest/submission/'.$id) , 'icon' => 'text-left' , 'text' => 'Submissions'])
	      @include('buttons.jump-icon' , ['href' => url('/contest/standings/'.$id) , 'icon' => 'statistics' , 'text' => 'Standings'])
	      @include('buttons.jump-icon' , ['href' => url('/problem/customtests/') , 'icon' => 'test-file' , 'text' => 'Custom tests'])
		  @if ($is_admin)
			<a class="mdui-btn mdui-color-theme-accent" href="/contest/edit/{{$id}}">
            <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </a>
          @endif
    	</div>
    </div>
    <br>

    <table class="mdui-table mdui-table-hoverable mdui-hoverable">
      <thead>
        <tr>
          <th style="width:22%"> Begin Time </th>
          <th style="width:22%"> End Time </th>
          <th style="width:22%"> Duration </th>
          <th style="width:22%"> Contest Status </th>
          <th style="width:10%"> Rule</th> 
        </tr>
      </thead>
      <tbody>
        <tr>
          <td> {{ $contest -> begin_time }} </td>
          <td> {{ $contest -> end_time }} </td>
          <td> 
            <?php
              $len = strtotime($contest -> end_time) - strtotime($contest -> begin_time);
              $str = sprintf("%02d:%02d:%02d", floor($len / 3600), floor($len % 3600 / 60), $len % 60);
              echo $str;
            ?>
          </td>
          <td>
            <b>
              @if(NOW() < $contest -> begin_time)
                <a class="text-primary"> Waiting : <span id="time_remained"> 00:00:00 </span> </a>
              @elseif(NOW() < $contest -> end_time)
                <a class="text-success"> Running : <span id="time_remained"> 00:00:00 </span> </a>
              @else
                <a class="text-danger"> Ended </a>
              @endif
            </b>
          </td>
			<td class="text-success"><b>
			@if($contest->rule==0)
				OI
			@elseif($contest->rule==1)
				IOI
			@elseif($contest->rule==2)
				ACM
			@endif
			</b></td>
        </tr>
      </tbody>
    </table>

    <script type="text/javascript"> 
      var info;
      function countTime(cnt) {  
        var h, m, s;
        if (cnt > 0) {
          h = Math.floor(cnt / 3600);  
          m = Math.floor(cnt % 3600 / 60);  
          s = Math.floor(cnt % 60);
        } else if (cnt <= 0) {
          location.reload();
          alert(info);
        }
        if (h < 10) h = "0" + h;
        if (m < 10) m = "0" + m;
        if (s < 10) s = "0" + s;
        document.getElementById("time_remained").innerHTML = h + ":" + m + ":" + s;  
        setTimeout("countTime(" + (cnt - 1) + ")", 1000);
      }
    </script>

    @if(NOW() < $contest -> begin_time)
      <?php $len = strtotime($contest -> begin_time) - strtotime(NOW()); ?>
      <script>
        info = "Contest Started!";
        countTime({{ $len }});
      </script>
    @elseif(NOW() < $contest -> end_time)
      <?php $len = strtotime($contest -> end_time) - strtotime(NOW()); ?>
      <script>
        info = "Contest Ended!";
        countTime({{ $len }});
      </script>
    @endif

	@if($contest->contest_info)
	<div class="mdui-card mdui-hoverable">
	<div class="mdui-card-primary">
		<div class="mdui-card-primary-title"> Annoucements: </div> 
	</div>
	<div class="mdui-card-content">
		<?php 
			echo $contest -> contest_info;
		?>
	</div>
	</div>
	@endif
	<br>
    @include("includes.contest_problem_table", ['cid' => $contest -> id, 'problemset' => $contest -> problemset]) 
@endsection
