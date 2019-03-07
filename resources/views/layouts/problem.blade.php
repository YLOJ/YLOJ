@extends("layouts.app")

@section("content")

<<<<<<< HEAD
<div class="container">
	<div class="row">
		<div class="col">
			<h1><center> @yield("title") </h1> <br>
			Time Limit : {{ $time_limit }} ms <br>
			Memory Limit : {{ $memory_limit }} M <br>
			
			<div class = "btn-group-md">
				<button class = "btn btn-primary" href="#"> submit </button>
				<button class = "btn btn-primary" href="#"> submissions </button>
				<button class = "btn btn-primary" href="#"> dicussions </button>
				<button class = "btn btn-primary" href="#"> statistics </button>
				<button class = "btn btn-primary" href="#"> custom tests </button>


				@auth
					@if ( Auth::user()->permission > 0)
					<button class = "btn btn-primary" href="javascript:void(0);" onclick="document.getElementById('myform').submit();"> edit </button>
					<form id="myform" method="post" action="/problem/edit/{{$id}}">
						@csrf
					</form>
					@endif
				@endauth
			</div>
			</center> <br>

			@yield("problem_content")
		
		</div>
	</div>
=======
<div class="artcle">
    <h1> @yield("title") </h1>

    <h3>
        <nav>
            <a href="#">submit</a> |
            <a href="#">submissions</a> |
            <a href="#">discussions</a> |
            <a href="#">statistics</a> |
            <a href="#">custom test</a>

            @auth
            @if ( Auth::user()->permission > 0)
            |<a href="javascript:void(0);" onclick="document.getElementById('myform').submit();">edit</a>
            <form id="myform" method="post" action="/problem/edit/{{$id}}">
                @csrf
            </form>
            @endif
            @endauth

        </nav>
    </h3>

    <hr>

    <h4>
        @yield("problem_content")
    </h4>
>>>>>>> 5c2445f303d5cd225ba82355ac6184bfd06262df
</div>

@endsection
