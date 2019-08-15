@extends('layouts.app')

@section('content')
  <div class="container">
    <h2> 
      Problem #{{ $id }} 
    </h3>
	<div>
	<div id="uploadFile" style="float: left">
    <form action="/problem/upload_file/{{$id}}" method="post" enctype="multipart/form-data">
      <label> <b> 上传文件: </b> </label> <br>
      <input type="file" name="source"> <br> <br>

      @include('buttons.submit',['text' => '上传'])
      @csrf
    </form>
	</div>
	<div id="fileList" style="float: left;width: 50%">
		<label> <b> 文件列表：</b> </label><br>	
		<table class="table table-bordered">
		  <thead>
		    <tr>
			  <th style="width: 70%">文件名</th>
			  <th style="width: 10%">删除</th>
		    </tr>
		  </thead>
		  <tbody>
		    @foreach ($filelist as $one)
		      @if ($loop -> index % 2 == 0) <tr style="background-color:#F3F3F3">
		      @else <tr>
		      @endif
		      <td> <a href='/problem/{{$id}}/{{$one}}'>{{ $one }}</a> </td><td><a href='/problem/delete_file/{{$id}}/{{$one}}'><img src="{{ asset('svg/icons/delete.svg') }}"/></a> </td>
		      </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
	</div>
  </div>
@endsection
