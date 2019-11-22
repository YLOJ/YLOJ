@extends('layouts.app')

@section('content')

@if ($errors->any())
	<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
	</div>
@endif

   <h2><a href="{{url('/problem/'.$id)}}">Problem #{{$id}}</a> </h2>
   <form action="/problem/edit_submit/{{$id}}" method="post">
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Title</label>
	  <input class="mdui-textfield-input" type="text" name="title"  value="{{$title}}" required/>
	</div>
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Content</label>
	  <textarea class="mdui-textfield-input" type="text" rows=20 name="content_md"> {{$content_md}}</textarea>
	</div>
    <div class="form-check">
      <label>
     	公开性	
       </label>
	<br>
	<label class="mdui-radio">
	  <input type="radio" name="visibility" value=2 {{$visibility==2?'checked':''}}/>
	  <i class="mdui-radio-icon"></i>隐藏
	</label>
	<label class="mdui-radio">
	  <input type="radio" name="visibility" value=1 {{$visibility==1?'checked':''}}/>
	  <i class="mdui-radio-icon"></i>权限
	</label>
	<label class="mdui-radio">
	  <input type="radio" name="visibility" value=0 {{$visibility==0?'checked':''}}/>
	  <i class="mdui-radio-icon"></i>默认
	</label>
    </div>
      @include('buttons.submit' , ['text' => 'Save'])
      @csrf
   </form>

	<br>
    <div class="mdui-btn-group">
    @include('buttons.jump', ['href' => url('/problem/upload/'.$id) , 'text' => 'Upload Files'])

    @include('buttons.jump', ['href' => url('/problem/data/'.$id) , 'text' => 'Manage Data'])

    @include('buttons.jump', ['href' => url('/problem/edit/manager/'.$id) , 'text' => 'Manage Managers'])

    @include('buttons.jump', ['href' => url('/problem/solution/edit/'.$id) , 'text' => 'Manage Solution'])
	</div>
	<br>
	<br>
	<div class="mdui-btn-group">
    @include('buttons.jump-col', ['href' => '/submission/delete_problem/'.$id , 'text' => 'Delete All Submission'])
    &nbsp &nbsp
    @include('buttons.jump-col', ['href' => '/submission/rejudge_problem/'.$id , 'text' => 'Rejudge All Submission'])
    &nbsp &nbsp
    @include('buttons.jump-col', ['href' => '/submission/rejudge_problem_ac/'.$id , 'text' => 'Rejudge All AC Submission'])
	</div>
  </div>
@endsection
