@extends('layouts.app')

@section('content')

<?php
if (file_exists("../storage/problems/" . $id)) {
    $list = scandir("../storage/problems/" . $id);
    foreach ($list as $file_name) { 
        echo $file_name."<br>";
    }
} else echo "no data exists";
?>

<form action="/problem/edit/data_submit/{{$id}}" method="post" enctype="multipart/form-data">
    <label>upload file:</label>
    <input type="file" name="data"><br>
    <input type="submit" value="upload">
    @csrf
</form>

@endsection