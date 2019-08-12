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
    <h3><a href="{{url('/problem/'.$id)}}">Problem #{{$id}}</a> </h3>
    <form action="/problem/edit_submit/{{$id}}" method="post">
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{$title}}">
      </div>
      <div class="form-group">
        <label>Content</label> <br>
        <textarea rows="16" name="content_md" , class="form-control">{{$content_md}}</textarea>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="visibility" {{$visibility?'checked':''}} value=1>
        <label class="form-check-label" for="visibility">
          Visibility
        </label>
      </div>
      <br>
      @include('buttons.submit',['text' => 'Save'])
      @csrf
    </form>

    <br>
    @include('buttons.jump', ['href' => url('/problem/data/'.$id) , 'text' => 'Manage Data'])

    @include('buttons.jump', ['href' => url('/problem/edit/manager/'.$id) , 'text' => 'Manage Managers'])
    <br>
    <br>

    @include('buttons.jump-danger', ['href' => '/submission/delete_problem/'.$id , 'text' => 'Delete All Submission'])
    &nbsp &nbsp
    @include('buttons.jump-danger', ['href' => '/submission/rejudge_problem/'.$id , 'text' => 'Rejudge All Submission'])
    &nbsp &nbsp
    @include('buttons.jump-danger', ['href' => '/submission/rejudge_problem_ac/'.$id , 'text' => 'Rejudge All AC Submission'])

  </div>
@endsection
