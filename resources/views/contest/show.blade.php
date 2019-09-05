@extends('layouts.app')

@section('content')
  <div class="container">
    <br>
    <h1 style="text-align:center"> {{ $contest -> title }} </h1>
    <br>

    <?php $id = $contest -> id ?>
    <div class="btn-group-md" style="text-align:center">
      @include('buttons.jump-icon' , ['href' => url('/contest/mysubmission/'.$id) , 'icon' => 'text-left' , 'text' => 'My Submissions'])
      @include('buttons.jump-icon' , ['href' => url('/contest/submission/'.$id) , 'icon' => 'text-left' , 'text' => 'Submissions'])
      @include('buttons.jump-icon' , ['href' => url('/contest/standings/'.$id) , 'icon' => 'statistics' , 'text' => 'Standings'])
      @include('buttons.jump-icon' , ['href' => url('/problem/customtests/') , 'icon' => 'test-file' , 'text' => 'Custom tests'])

      @auth
        @if ( Auth::user()->permission > 1)
          <button class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
            <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </button>
          <form id="myform" method="post" action="/contest/edit/{{$id}}">
            @csrf </form>
        @endif
      @endauth
    </div>
    <br>

    <table class="table table-bordered">
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
			@if($contest->rule==0)
            	<td class="text-success"> <b> OI </b> </td>
			@elseif($contest->rule==1)
            	<td class="text-success"> <b> IOI </b> </td>
			@endif
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

    <hr>
    <h4> Annoucements: </h4> <br>
      {{ $contest -> contest_info }}
    <hr>

    <div class="row">
      <div class="col">
        @component("includes.contest_problem_table", ['cid' => $contest -> id, 'problemset' => $contest -> problemset]) 
        @endcomponent
      </div>
    </div>
  </div>
@endsection
