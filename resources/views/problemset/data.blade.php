@extends('layouts.app')

@section('content')
  <div class="container">
    <?php use Illuminate\Support\Facades\Storage; ?>

    <h2> 
      Problem #{{ $id }} : Manage Data
    </h2>

    @include('buttons.jump', ['href' => url('/problem/edit/'.$id) , 'text' => '回到编辑题目页面'])
    @if (Storage::disk('data')->exists($id))
      <h3 class="text-success"> Data Uploaded </h3>
    @else 
      <h3 class="text-danger"> No Data Exists </h3>
    @endif
	<div>
	<div id="uploadData" style='float: left'>
    <form action="/problem/data_submit/{{$id}}" method="post" enctype="multipart/form-data">
      <label> <b> 上传data.zip: </b> </label> <br>
      <input type="file" name="data"> <br> <br>

      @include('buttons.submit',['text' => '上传'])
      @csrf
    </form>
	<br>
    @include('buttons.jump', ['href' => url('/problem/data_download/'.$id) , 'text' => '下载数据'])
	</div>
	<div id="updateConfig" style='float: left'>
    <form action="/problem/save_config/{{$id}}" method="post" enctype="multipart/form-data">
      <label> <b> 修改config.yml: </b> </label> <br>
		<textarea name='config' rows=10>{{$config}}</textarea>
      <br> <br>
      @include('buttons.submit',['text' => '更新'])
      @csrf
	</form>
	</div>
	<div id="formatData" style='float: left'>
	<form action="/problem/data_format/{{$id}}" method="post" enctype="multipart/form-data">
	   <label><b> 选择题目类型 </b></label>

	    <select name="type" id="type">

	      <option value="0" selected>传统题</option>
	
	      <option value="1">交互题(OI style)</option>
	
	    </select>
		<script>
			$("#type").change(function(){
			if($("#type").val()==1)
				$("#type1").css("display","list-item");
			else
				$("#type1").css("display","none");
			});
		</script>
		<div name="type1" id="type1" style="display:none">
		头文件：<input name="header" >	
		</div>
		<br>
      <label> <b> 生成数据列表规则：（自动匹配留空） </b> </label> <br>

		<textarea name='matchrule' rows=10></textarea>
      @csrf
	  <br><br>
      @include('buttons.submit',['text' => '生成'])
	</form>
	<br>
	</div>
	<div id="formatResult" style='float: left'>
	@if($log!='')
		<pre><code>{{$log}}</code></pre>
	<br>
    	<form action="/problem/format_check/{{$id}}" method="post" enctype="multipart/form-data">
			  <button name="check" type="submit" value=1>确定</button>
			  <button name="check" type="submit" value=0>取消</button>
      		  @csrf
		</form>
	@else
	  <h4> 并没有生成过数据列表 </h4>
	@endif
	</div>
	</div>
	<br>
	
  </div>
@endsection
