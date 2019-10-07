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
                <button class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                  <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </button>
                <form id="myform" method="post" action="/problem/solution/edit/{{$id}}">
                  @csrf
                </form>
              @endif
            @endauth
          </div>
        </div>

        <br>
		<div class="content"></div>
		<script src=/js/app.js></script>
		<script>
			md=@json($content_md);

			$('.content').html(marked(md));
		</script>

      </div>
    </div>
  </div>
@endsection 
