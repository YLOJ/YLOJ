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
        <div>
            <label>Time Limit</label>
            <input type="text" name="time_limit" value="{{$time_limit}}">Ms 
            <label>Memory Limit</label>
            <input type="text" name="memory_limit" value="{{$memory_limit}}">MB
        </div>
        <div class="form-group">
            <label>Content</label> <br>
<<<<<<< HEAD
            <textarea rows="20" cols="0" name="content_md", class="form-control">
{{$content_md}}
            </textarea>
=======
            <textarea rows="20" name="content_md", class="form-control">{{$content_md}}</textarea>
>>>>>>> 2cdd5e98ce4f60f8d7f8f8129e0949286dacc430
        </div>
        <button type="submit" class="btn btn-primary"> Save </button>
        @csrf
    </form>
</div>
@endsection