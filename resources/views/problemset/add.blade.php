@extends('layouts.app')

@section('content')

<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/problem/add_submit" method="post">    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-inline">
            <label>Time Limit &nbsp </label>
            <input type="text" name="time_limit" class="form-control input-sm" value="1000">Ms 
            <label> &nbsp&nbsp Memory Limit &nbsp </label>
            <input type="text" name="memory_limit" class="form-control input-sm" value="256">Mb
        </div>
        <div class="form-group">
            <label>Content</label> <br>
            <textarea rows="20" name="content_md", class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"> Add </button>
        @csrf
    </form>
</div>
@endsection