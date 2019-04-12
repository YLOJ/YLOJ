@extends('layouts.app')

@section('content')

<div class="container">
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
	<button type="submit" class="btn btn-primary"> upload </button>
    @csrf
</form>

<br/>

<?php
	if ( Storage::disk('problems')->exists($id)) {
		echo '<a href="/problem/data_download/'.$id.'">
		<button class="btn btn-primary"> download </button>
		</a>';
	}
?>
</div>

@endsection
