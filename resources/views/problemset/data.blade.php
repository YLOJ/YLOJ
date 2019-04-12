@extends('layouts.app')

@section('content')

<div class="container">

<?php
use Illuminate\Support\Facades\Storage;
?>

<h2> Problem #{{ $id }} : Manage Data</h2>

@if (Storage::disk('problems')->exists($id))
	<h3 class="text-success"> Data Uploaded </h3>
@else 
	<h3 class="text-danger"> No Data Exists </h3>
@endif
<br>

<form action="/problem/data_submit/{{$id}}" method="post" enctype="multipart/form-data">
	<label> <b> Upload data.zip: </b> </label> <br>
	<input type="file" name="data"> <br> <br>
	<button type="submit" class="btn btn-danger"> Upload </button>
	@csrf
</form>

<br>

@if (Storage::disk('problems')->exists($id)) 
	<a href="/problem/data_download/{{ $id }}">
		<button class="btn btn-primary"> Download </button>
	</a>
@endif
</div>

@endsection
