@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Storage; ?>

   <h2><a href="{{url('/problem/'.$id)}}">Problem #{{$id}}</a> </h2>

    @include('buttons.jump', ['href' => url('/problem/edit/'.$id) , 'text' => '回到编辑题目页面'])
	<div class="mdui-tab" mdui-tab>
	  <a href="#uploadData" class="mdui-ripple {{$page==1?'mdui-tab-active':''}}">上传数据</a>
	  <a href="#updateConfig" class="mdui-ripple  {{$page==2?'mdui-tab-active':''}}">修改数据配置文件</a>
	  <a href="#matchData" class="mdui-ripple  {{$page==3?'mdui-tab-active':''}}">匹配数据</a>
	</div>
	<div>
	<div id="uploadData"  class="mdui-p-a-2">
    @if (Storage::disk('data')->exists($id))
      <h3 class="text-success"> Data Uploaded </h3>
    @else
      <h3 class="text-danger"> No Data Exists </h3>
    @endif
    <form action="/problem/data_submit/{{$id}}" method="post" enctype="multipart/form-data">
      <label> <b> 上传data.zip: </b> </label> <br>
      <input type="file" name="data"> <br> <br>

      @include('buttons.submit',['text' => '上传'])
      @csrf
    </form>
	<br>
    @include('buttons.jump', ['href' => url('/problem/data_download/'.$id) , 'text' => '下载数据'])
	</div>
	<div id="updateConfig"  class="mdui-p-a-2">
    <form action="/problem/save_config/{{$id}}" method="post" enctype="multipart/form-data">
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">Config</label>
	  <textarea class="ace-editor-base" ace_language='yaml'  class="mdui-textfield-input" type="text" rows=10 name="config">{{$config}}</textarea>
	</div>

      <br> <br>
      @include('buttons.submit',['text' => '更新'])
      @csrf
	</form>
	</div>
	<div id="matchData" class="mdui-p-a-2">
	<form action="/problem/data_match/{{$id}}" method="post" enctype="multipart/form-data">
	   <label><b> 选择题目类型 </b></label>

	    <select name="type" id="type" class="mdui-select">

	      <option value="0" selected>传统题</option>

	      <option value="1">交互题(OI style)</option>

	      <option value="2">交互题(IO style)</option>

	    </select>
		<script>
			$("#type").change(function(){
			if($("#type").val()==1)
				$("#type1").css("display","list-item");
			else
				$("#type1").css("display","none");
			});
		</script>
		<div class="mdui-textfield mdui-textfield-floating-label" id="type1" style="display:none">
		  <label class="mdui-textfield-label">头文件名</label>
		  <input  class="mdui-textfield-input" type="text" name="header"/>
		</div>
		<div class="mdui-textfield">
		  <label class="mdui-textfield-label">匹配规则(自动匹配留空)</label>
		  <textarea class="ace-editor-base" class="mdui-textfield-input" type="text" rows=10 name="matchrule"></textarea>
		</div>


      @csrf
	  <br><br>
      @include('buttons.submit',['text' => '生成'])
	</form>
	<br>
	<div id="matchResult">
	@if($log!='')
		<pre class="language-none"><code>{{$log}}</code></pre>
	<br>
    	<form action="/problem/match_check/{{$id}}" method="post" enctype="multipart/form-data">
			  <button name="check" type="submit" value=1 class="mdui-btn mdui-btn-dense mdui-color-theme">确定</button>
			  <button name="check" type="submit" value=0  class="mdui-btn mdui-btn-dense mdui-color-theme">取消</button>
      		  @csrf
		</form>
	@else
	  <h4> 并没有生成过数据列表 </h4>
	@endif
	</div>

	</div>
	</div>
	<br>

  </div>
@endsection
