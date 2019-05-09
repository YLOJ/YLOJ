@extends('layouts.app')

@section('content')
  <div class="container">
    <br>
    <h2 style='text-align:center;'> Standinds </h2>
    <br>
    <table id="standings" class="table table-bordered tablesort">
      <thead>
        <tr>
          <th style="width:20%">User Name</th>
          @foreach ($contest -> problemset as $problem)
            <th> {{ $problem -> title }} </th>
          @endforeach
          <th style="width:20%">Total Score</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($standings as $user)
          @if ($loop -> index % 2 == 0) <tr style="background-color:#F3F3F3">
          @else <tr>
          @endif
          <td> {{ $user -> user_name }} </td>
          @foreach($user -> result as $sub)
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
          @endforeach
          <td class='text-primary'> <b> {{ $user -> score }} </b> </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <script type="text/javascript" defer>
    $(document).ready(function() { 
      $("#standings").tablesorter(); 
    }); 
  </script>
@endsection
