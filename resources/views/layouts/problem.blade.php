@extends("layouts.app")

@section("content")

<div class="artcle">
    <h1> @yield("title") </h1>
<<<<<<< HEAD
    
    <h5><nav>
        <a href="#">submit</a> |
        <a href="#">submissions</a> |
        <a href="#">discussions</a> |
        <a href="#">statistics</a> |
        <a href="#">custom test</a>

        @auth
=======

    <h3>
        <nav>
            <a href="#">submit</a> |
            <a href="#">submissions</a> |
            <a href="#">discussions</a> |
            <a href="#">statistics</a> |
            <a href="#">custom test</a>

            @auth
>>>>>>> 2cdd5e98ce4f60f8d7f8f8129e0949286dacc430
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