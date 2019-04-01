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
    <form action="/problem/edit_submit/{{$id}}" method="post">    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{$title}}">
        </div>
        <div class="form-group">
            <label>Time Limit</label>
            <input type="text" name="time_limit" value="{{$time_limit}}">Ms 
            <label>Memory Limit</label>
            <input type="text" name="memory_limit" value="{{$memory_limit}}">Mb
        </div>
        <div class="form-group">
            <label>Content</label> <br>
            <textarea rows="20" name="content_md", class="form-control">{{$content_md}}</textarea>
        </div>
        <button type="submit" class="btn btn-primary"> Save </button>
        @csrf
    </form>
</div>
@endsection
