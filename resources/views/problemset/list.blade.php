@extends('layouts.app')

@section('content')

<div>
    @foreach ($problemset as $problem)
        <a href="/problemset/{{$problem->id}}"> {{$problem->title}} </a> <br>
    @endforeach
</div>

{{ $problemset->links() }}

@endsection