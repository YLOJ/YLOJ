@extends('layouts.app')

@section('content')
  <div class="container">
	<h2> 管理后台 </h2>
	<h3> 批量创建比赛 </h3>
	<form action="/webadmin/create_contest" method="post">
      	<div class="form-inline">
			<label> &nbsp 题数 &nbsp </label>
			<input name="task_num"></input>
			<label> &nbsp&nbsp 赛制 &nbsp </label>
	        <select id="rule" name="rule" class="form-control">
	          <option value="0" selected> OI </option>
	          <option value="1"> IOI </option>
	          <option value="2"> ACM </option>
	        </select>
		</div>

		<label>用户列表</label>
        <textarea rows="16" name="userlist" class="form-control"></textarea>
		<br>

      	@include('buttons.submit',['text' => '更新'])
		@csrf	
	</form>
  </div>
@endsection
