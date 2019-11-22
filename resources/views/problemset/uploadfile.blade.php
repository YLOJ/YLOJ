@extends('layouts.app')

@section('content')
   <h2><a href="{{url('/problem/'.$id)}}">Problem #{{$id}}</a> </h2>
    @include('buttons.jump', ['href' => url('/problem/edit/'.$id) , 'text' => '回到编辑题目页面'])
	<div>
	<div id="upload_file" style="float: left">
    <form action="/problem/upload_file/{{$id}}" method="post" enctype="multipart/form-data">
      <label> <b> 上传文件: </b> </label> <br>
      <input type="file" name="source"> <br> <br>

      @include('buttons.submit',['text' => '上传'])
      @csrf
    </form>
	</div>
	<div id="file_list" style="float: left">
		<label> <b> 文件列表：</b> </label><br>	
		<table class="mdui-table mdui-table-hoverable mdui-hoverable">
		  <thead>
		    <tr>
			  <th style="width: 20%">文件名</th>
			  <th style="width: 10%">删除</th>
			  <th style="width: 50%">链接</th>
		    </tr>
		  </thead>
		  <tbody>
		    @foreach ($filelist as $one)
		      <tr>
			  <td> <a href='/problem/{{$id}}/{{$one}}'>{{ $one }}</a> </td><td><a href='/problem/delete_file/{{$id}}/{{$one}}'><img src="{{ asset('svg/icons/delete.svg') }}" class="icon-md"/></a> </td><td><code>[{{$one}}](/problem/{{$id}}/{{$one}})</code></td>
		      </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
	</div>
@endsection
