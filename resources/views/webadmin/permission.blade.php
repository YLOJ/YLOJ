@extends('layouts.app')

@section('content')
  <div class="container">
	<h2> 管理后台 </h2>
	<h3> 权限管理 </h3>
	<form action="/webadmin/update_permission" method="post">
		<label>用户列表</label>
        <textarea rows="16" name="userlist" class="form-control"></textarea>
		<label>类型</label>
        <select id="type" name="type" class="form-control">
          <option value="0"> 增加权限</option>
          <option value="1"> 去掉权限</option>
        </select>

      	@include('buttons.submit',['text' => '更新'])
		@csrf	
	</form>
  </div>
@endsection
