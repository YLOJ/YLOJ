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

   <form action="/problem/add_submit" method="post">
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Title</label>
	  <input class="mdui-textfield-input" type="text" name="title" required/>
	</div>
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">Content</label>
	  <textarea class="ace-editor-base" ace_language='markdown' class="mdui-textfield-input" type="text" name="content_md" rows=20></textarea>
	</div>
      @include('buttons.submit' , ['text' => 'Add'])
      @csrf
   </form>
@endsection
