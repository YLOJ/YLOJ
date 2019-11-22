@extends("layouts.app")

@section("content")
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="text-center">
          <h1> #{{$id}}. {{ $title }} </h1>

          <div class="btn-group-md">
            @auth
              @if ($is_admin)

                <button class="mdui-btn mdui-color-theme-accent" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                  <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </button>
                <form id="myform" method="post" action="/problem/solution/edit/{{$id}}">
                  @csrf
                </form>
              @endif
            @endauth
          </div>
        </div>

        <br>

		<div class="mdui-card mdui-hoverable">
			<div class="mdui-card-content"><?php
				echo $content;?>
			</div>
		</div>

      </div>
    </div>
  </div>
@endsection 
