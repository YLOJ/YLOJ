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
        <label>标题</label>
        <input type="text" name="title" class="form-control" value="{{$title}}">
      </div>
      <div class="form-group">
        <label>内容</label> <br>
        <textarea rows="16" name="content_md" , class="form-control">{{$content_md}}</textarea>
      </div>
      <div class="form-check">
        <label>
        	公开性	
        </label>
		<br>
 		<input type="radio" name="visibility" value=2 {{$visibility==2?'checked':''}}/>隐藏
		<br>
 		<input type="radio" name="visibility" value=1 {{$visibility==1?'checked':''}}/>权限
		<br>
 		<input type="radio" name="visibility" value=0 {{$visibility==0||!$visibility?'checked':''}}/>默认

      </div>
      <br>
      @include('buttons.submit',['text' => 'Save'])
      @csrf
    </form>

    <br>
    @include('buttons.jump', ['href' => url('/problem/upload/'.$id) , 'text' => 'Upload Files'])

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
