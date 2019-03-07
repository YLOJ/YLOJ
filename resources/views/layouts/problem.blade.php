@extends("layouts.app")

@section("content")

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
</div>

@endsection