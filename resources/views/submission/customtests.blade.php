@extends('layouts.app')

@section('content')

@if( isset($jid) )
    jid={{$jid}}
@else
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
    </div>
@endif

@endsection