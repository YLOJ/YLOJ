@extends('layouts.app')

@section('content')
	<h2> 管理后台 </h2>
	<div class="mdui-list">
		<a href="/webadmin/permission" class="mdui-list-item mdui-ripple">权限管理</a>
		<a href="/webadmin/contest" class="mdui-list-item mdui-ripple">批量创建比赛</a>
	</div>
@endsection
