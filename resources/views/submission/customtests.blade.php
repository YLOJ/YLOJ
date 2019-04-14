@extends('layouts.app')

@section('content')
<div class="container">
    <form action="#" method="post">
        <div class="form-group">
            <label>Source Code</label> <br>
            <textarea rows="15" name="source_code" , class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Input Text File</label> <br>
            <textarea rows="5" name="source_code" , class="form-control"></textarea>
        </div>
        @include('buttons.submit' , ['text' => 'Submit'])
        @csrf
    </form>
</div>
@endsection