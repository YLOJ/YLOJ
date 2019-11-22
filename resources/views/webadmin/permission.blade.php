@extends('layouts.app')

@section('content')
	<h2> 管理后台 </h2>
	<h3> 权限管理 </h3>
	<form action="/webadmin/update_permission" method="post">
		<div class="mdui-textfield mdui-textfield-floating-label">
		  <label class="mdui-textfield-label">用户列表</label>
		  <textarea class="mdui-textfield-input" type="text" rows=20 name="userlist"></textarea>
		</div>

		<br>
		<label>类型</label>
        <select id="type" name="type" class="mdui-select">
          <option value="0"> 增加权限</option>
          <option value="1"> 去掉权限</option>
        </select>
		<br>
		<br>
      	@include('buttons.submit',['text' => '更新'])
		@csrf	
	</form>
@endsection
