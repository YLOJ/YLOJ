@extends("layouts.app")

@section("content")

<div class="artcle">
    <h1> @yield("title") </h1>

    <h5><nav>
        <a href="#">submit</a> |
        <a href="#">submissions</a> |
        <a href="#">discussions</a> |
        <a href="#">statistics</a> |
        <a href="#">custom test</a>
    </nav></h5>

    <hr>

    @yield("problem_content")
</div>

@endsection