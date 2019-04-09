@extends("layouts.app")

@section("content")

<div class="container">
    <div class="row">
        <div class="col">
            <div class="text-center">
                <h1> @yield("title") </h1>
                Time Limit : {{ $time_limit }} Ms <br>
                Memory Limit : {{ $memory_limit }} Mb <br> <br>

                <div class="btn-group-md">
					<a href="{{ url('/problem/submit/'.$id) }}">
						<button class="btn btn-sm btn-primary" >
							<img src="{{ asset('svg/icons/paper-plane.ico') }}" /> Submit 
						</button>
					</a>
					<a href="#">
						<button class="btn btn-sm btn-primary" href="#">
							<img src="{{ asset('svg/icons/text-left.ico') }}" /> Submissions 
						</button>
					</a>
					<a href="#">
						<button class="btn btn-sm btn-primary" href="#">
							<img src="{{ asset('svg/icons/discussion.ico') }}" /> Dicussions 
						</button>
					</a>
					<a href="#">
						<button class="btn btn-sm btn-primary" href="#">
							<img src="{{ asset('svg/icons/statistics.ico') }}" /> Statistics 
						</button>
					</a>
					<a href="#">
						<button class="btn btn-sm btn-primary" href="#">
							<img src="{{ asset('svg/icons/test-file.ico') }}" /> Custom tests 
						</button>
					</a>


                    @auth
                    @if ( Auth::user()->permission > 0)
                    <button class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                        <img src="{{ asset('svg/icons/edit.ico') }}" /> Edit </button>
                    	<form id="myform" method="post" action="/problem/edit/{{$id}}">
                        @csrf
                    </form>
                    @endif
                    @endauth
                </div>
            </div>

            <br>

            @yield("problem_content")

        </div>
    </div>
</div>

@endsection 
