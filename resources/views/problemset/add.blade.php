@extends('layouts.app')

@section('content')

<form method="post" action="/problem/add_submit">
title : <input type="text" name="title"> <br>
content(markdown) : <br> <textarea rows="20" cols="60" name="content_md">
</textarea><br>
<button type="submit">submit</button>

</form>

@endsection