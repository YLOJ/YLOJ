@extends("layouts.app")

@section("content")

<div class="container">
    <div class="row">
        <div class="col">
            <div class="text-center">
                <h1> {{ $title }} </h1>
                Time Limit : {{ $time_limit }} Ms <br>
                Memory Limit : {{ $memory_limit }} Mb <br> <br>

                <div class="btn-group-md">
					<a href="{{ url('/problem/submit/'.$id) }}">
						<button class="btn btn-sm btn-primary" >
							<img src="{{ asset('svg/icons/paper-plane.ico') }}" class="icon-sm"/> Submit 
						</button>
					</a>
					<a href="{{ url('/submission?problem_id='.$id) }}">
						<button class="btn btn-sm btn-primary">
							<img src="{{ asset('svg/icons/text-left.ico') }}" class="icon-sm"/> Submissions 
						</button>
					</a>
					<a href="{{ url('/problem/statistics/'.$id) }}">
						<button class="btn btn-sm btn-primary">
							<img src="{{ asset('svg/icons/statistics.ico') }}" class="icon-sm"/> Statistics 
						</button>
					</a>
					<a href="{{ url('/problem/customtests') }}">
						<button class="btn btn-sm btn-primary">
							<img src="{{ asset('svg/icons/test-file.ico') }}" class="icon-sm"/> Custom tests 
						</button>
					</a>


                    @auth
                    @if ( Auth::user()->permission > 0)
                    <button class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                        <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </button>
                    	<form id="myform" method="post" action="/problem/edit/{{$id}}">
                        @csrf
                    </form>
                    @endif
                    @endauth
                </div>
            </div>

            <br>

            <?php echo $content_html ?>

        </div>
    </div>
</div>

@endsection 
