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

    <form action="/contest/edit_submit/{{ $contest -> id }}" method="post">    
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="{{ $contest -> title }}" class="form-control">
      </div>
      <div class="form-inline">
        <label> From &nbsp </label>
        <input type="text" name="begin_time" value="{{ $contest -> begin_time }}"class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp To &nbsp </label>
        <input type="text" name="end_time" value="{{ $contest -> end_time }}"class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp&nbsp Rule &nbsp </label>
        <select id="rule" name="rule" class="form-control">
          <option value="0" selected> OI </option>
        </select>
      </div>
      <div class="form-group">
        <p class="text-small"> </p>
        <label> Problem IDs (divide with ',' and avoid using spaces, e.g.'233,234,236') </label>
        <input type="text" name="problemset" value="{{ $contest -> problemset }}" class="form-control"> 
      </div>
      <div class="form-group">
        <label> Contest Info </label> <br>
        <textarea rows="20" name="contest_info" class="form-control"> {{ $contest -> contest_info }} </textarea>
      </div>
      @include('buttons.submit' , ['text' => 'Save'])
      @csrf
    </form>
  </div>
@endsection
