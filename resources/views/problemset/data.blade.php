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
	@include('buttons.submit',['text' => 'Upload'])
	@csrf
</form>

<br>

@if (Storage::disk('problems')->exists($id)) 
	@include('buttons.jump',['href' => '/problem/data_download/'.$id , 'text' => 'Download'])
@endif <br> <br>
</div>

@endsection
