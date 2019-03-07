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
        <div>
            <label>Time Limit</label>
            <input type="text" name="time_limit" value="1000">Ms 
            <label>Memory Limit</label>
            <input type="text" name="memory_limit" value="256">MB
        </div>
        <div class="form-group">
            <label>Content</label> <br>
            <textarea rows="20" name="content_md", class="form-control">
            </textarea>
        </div>
        <button type="submit" class="btn btn-primary"> Add </button>
        @csrf
    </form>
</div>
@endsection