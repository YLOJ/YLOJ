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
    <h3><a href="{{url('/problem/solution/'.$id)}}">Problem #{{$id}} Solution</a> </h3>

    @include('buttons.jump', ['href' => url('/problem/solution/upload/'.$id) , 'text' => 'Upload Files'])

    @include('buttons.jump', ['href' => url('/problem/edit/'.$id) , 'text' => 'Edit Problem'])
	<br>
    <form action="/problem/solution/edit_submit/{{$id}}" method="post">
      <div class="form-group">
        <label>内容</label> <br>
        <textarea rows="16" name="content_md" , class="form-control">{{$content_md}}</textarea>
      </div>
      <br>
      @include('buttons.submit',['text' => 'Save'])
      @csrf
    </form>
  </div>
@endsection
