@extends('layouts.app')

@section('content')
<div class="container">
    <form action="#" method="post">
        <div class="form-group">
            <label>Source Code</label> <br>
            <textarea rows="20" name="source_code", class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"> Submit </button>
        @csrf
    </form>
</div>
@endsection
