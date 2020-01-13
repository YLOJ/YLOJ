@extends("layouts.app")

@section("content")
        <div class="text-center">
          <h1> #{{$id}}. {{ $title }} </h1>


				<a class="mdui-btn mdui-color-theme-accent mdui-btn-raised" href="/problem/{{$id}}">
				<img src="{{ asset('svg/icons/paper-plane.ico') }}" class="icon-sm"/>  Back</a>
            @auth
              @if ($is_admin)

                <button class="mdui-btn mdui-color-theme-accent mdui-btn-raised" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                  <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </button>
                <form id="myform" method="post" action="/problem/solution/edit/{{$id}}">
                  @csrf
                </form>
              @endif
            @endauth
          </div>

        <br>

		<div class="mdui-card mdui-hoverable">
			<div class="mdui-card-content"><?php
				echo $content;?>
			</div>
		</div>
@endsection 
