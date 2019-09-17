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
        <label> From &nbsp </label>
        <input type="text" name="begin_time" class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp To &nbsp </label>
        <input type="text" name="end_time" class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp&nbsp Rule &nbsp </label>
        <select id="rule" name="rule" class="form-control">
          <option value="0" selected> OI </option>
          <option value="1"> IOI </option>
          <option value="2"> ACM </option>
        </select>
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
