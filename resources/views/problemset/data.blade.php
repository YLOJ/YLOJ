@extends('layouts.app')

@section('content')

<?php

use Illuminate\Support\Facades\Storage;

if ( Storage::disk('problems')->exists($id)) {
    $list = Storage::disk('problems')->files($id);
    foreach ($list as $file_name) { 
        echo $file_name."<br>";
    }
} else echo "no data exists";
?>

<br>

<form action="/problem/data_submit/{{$id}}" method="post" enctype="multipart/form-data">
    <label>Upload 'data.zip':</label>
    <input type="file" name="data"><br>
    <input type="submit" value="upload">
    @csrf
</form>

@endsection