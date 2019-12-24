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
    <h2><a href="{{url('/problem/solution/'.$id)}}">Problem #{{$id}} Solution</a> </h2>

    @include('buttons.jump', ['href' => url('/problem/solution/upload/'.$id) , 'text' => 'Upload Files'])

    @include('buttons.jump', ['href' => url('/problem/edit/'.$id) , 'text' => 'Edit Problem'])
	<br>
    <form action="/problem/solution/edit_submit/{{$id}}" method="post">
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Content</label>
	  <textarea use_ace='true' class="mdui-textfield-input" type="text" rows=20 name="content_md"> {{$content_md}}</textarea>
	</div>

      <br>
      @include('buttons.submit',['text' => 'Save'])
      @csrf
    </form>
@endsection
