@extends('layouts.app')

@section('content')
  <div class="container">
	<h2> 管理后台 </h2>
	<h3> 批量创建比赛 </h3>
	<form action="/webadmin/create_contest" method="post">
		<div class="mdui-textfield mdui-textfield-floating-label">
		  <label class="mdui-textfield-label">题数</label>
		  <input class="mdui-textfield-input" type="text" name="task_num" required/>
		</div>
		<label> &nbsp&nbsp 赛制 &nbsp </label>
        <select id="rule" name="rule" class="mdui-select">
          <option value="0" selected> OI </option>
          <option value="1"> IOI </option>
          <option value="2"> ACM </option>
        </select>
		<br>
		<div class="mdui-textfield mdui-textfield-floating-label">
		  <label class="mdui-textfield-label">用户列表</label>
		  <textarea class="mdui-textfield-input" rows=20 name="userlist"></textarea>
		</div>

		<br>

      	@include('buttons.submit',['text' => '更新'])
		@csrf	
	</form>
  </div>
@endsection
