@extends("layouts.app")

@section("content")
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="text-center">
          <h1> {{ $title }} </h1>
			<?php
				echo $head;
			?>
          <div class="btn-group-md">
            @include('buttons.jump-icon' , ['href' => url('/contest/'.$cid.'/submit/'.$pid) , 'icon' => 'paper-plane' , 'text' => 'Submit'])
            @include('buttons.jump-icon' , ['href' => url('/contest/submission/'.$cid.'?problem_id='.$pid) , 'icon' => 'text-left' , 'text' => 'Submissions'])
            @include('buttons.jump-icon' , ['href' => url('/problem/statistics/'.$pid) , 'icon' => 'statistics' , 'text' => 'Statistics'])
            @include('buttons.jump-icon' , ['href' => url('/problem/customtests/') , 'icon' => 'test-file' , 'text' => 'Custom tests'])
			@if ($ended)
	            @include('buttons.jump-icon' , ['href' => url('/problem/solution/'.$pid) , 'icon' => 'test-file' , 'text' => 'Solution'])
			@endif
            @auth
              @if ( Auth::user()->permission > 1)
                <button class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                  <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit 
                </button>
                <form id="myform" method="post" action="/problem/edit/{{$pid}}">
                  @csrf
                </form>
              @endif
            @endauth
          </div>
        </div>
        <br>
		<div class="content"><?php echo $content;?></div>

      </div>
    </div>
  </div>
@endsection 
