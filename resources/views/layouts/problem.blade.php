@extends("layouts.app")

@section("content")

<div class="container">
	<div class="row">
		<div class="col">
			<h1><center> @yield("title") </h1>
			<center> Time Limit : {{ $time_limit }} ms </center>
			<center> Memory Limit : {{ $memory_limit }} M </center> <br>

			<center>
			<div class = "btn-group-md">
				<button class = "btn btn-primary" href="#"> 
					<img src="{{ asset('svg/icons/paper-plane.ico') }}"/> Submit </button>
				<button class = "btn btn-primary" href="#"> 
					<img src="{{ asset('svg/icons/text-left.ico') }}"/> Submissions </button>
				<button class = "btn btn-primary" href="#"> 
					<img src="{{ asset('svg/icons/discussion.ico') }}"/> Dicussions </button>
				<button class = "btn btn-primary" href="#"> 
					<img src="{{ asset('svg/icons/statistics.ico') }}"/> Statistics </button>
				<button class = "btn btn-primary" href="#"> 
					<img src="{{ asset('svg/icons/test-file.ico') }}"/> Custom tests </button>


				@auth
					@if ( Auth::user()->permission > 0)
					<button class = "btn btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();"> 
						<img src="{{ asset('svg/icons/edit.ico') }}"/> Edit </button>
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
</div>

@endsection
