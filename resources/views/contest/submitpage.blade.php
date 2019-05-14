@extends('layouts.app')

@section('content')
  <div class="container">
    <h4> 
      Source Code
      <a href="{{url('/contest/'.$cid.'/problem/'.$pid)}}"> 
        {{$title}}
      </a> 
    </h4>
    <form action="{{url('/contest/'.$cid.'/submit/'.$pid)}}" method="post">
      <div class="form-group">
        <textarea rows="20" name="source_code" class="form-control"></textarea>
      </div>
      @include('buttons.submit' , ['text' => 'Submit'])
      @csrf
    </form>
  </div>
@endsection
