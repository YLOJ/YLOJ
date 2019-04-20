@extends('layouts.app')

@section('content')

<div class="container">
    <form action="{{action('SubmissionController@customtests_submit') }}" method="post">
        <div class="form-group">
            <label>Source Code</label> <br>
            <textarea rows="15" name="source_code" , class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Input Text File</label> <br>
            <textarea rows="5" name="input_file" , class="form-control"></textarea>
        </div>
        @include('buttons.submit' , ['text' => 'Submit'])
        @csrf
    </form>

    @if( isset($jid) )
    time: {{ $time_used }} ms <br>
    memory: {{ $memory_used }} kb <br>

    <form action="{{action('SubmissionController@customtests_download')}}" method="post">
        <input type="hidden" name="jid" value="{{$jid}}">
        @include('buttons.submit',['text' => 'Output File Download'])
        @csrf
    </form>
    @endif
</div>

@endsection