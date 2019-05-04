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

    <form action="/contest/add_submit" method="post">    
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control">
      </div>
      <div class="form-inline">
        <label> Begin At &nbsp </label>
        <input type="text" name="begin_time" class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp&nbsp End At &nbsp </label>
        <input type="text" name="end_time" class="flatpickr form-control bg-white" placeholder="Pick date and time">
      </div>
      <div class="form-group">
        <label> Contest Info </label> <br>
        <textarea rows="20" name="contest_info" class="form-control"></textarea>
      </div>
      @include('buttons.submit' , ['text' => 'Add'])
      @csrf
    </form>

  </div>
@endsection
