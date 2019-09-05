@extends('layouts.app')

@section('content')
  <div class="container">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <h3><a href="{{url('/problem/'.$id)}}">Problem #{{$id}}</a> </h3>

    @include('buttons.jump', ['href' => url('/contest/edit/'.$id) , 'text' => '回到编辑比赛页面'])
	<p>
	用法：

	每行+pid 或者 -pid 表示 加入这个道题或者减掉这道题
	</p>
	<div id='contest_problemset_update' style='vertical-align:top;display:inline-block;width:49%'>
    <form action="/contest/problemset_update/{{$id}}" method="post">
      <div class="form-group">
        <textarea rows="16" name="content" class="form-control"></textarea>
      </div>
      <br>
      @include('buttons.submit',['text' => '更新'])
      @csrf
    </form>
	</div>
	<div id='contest_problemset_table' style='vertical-align:top;display:inline-block;width:49%'>
		<h3>当前题目列表</h3>
		<table class="table table-bordered">
		  <thead>
		    <tr>
		      <th style="width:20%">id</th>
		      <th style="width:80%">title</th>
		    </tr>
		  </thead>
		  <tbody>
		    @foreach ($problemset as $one)
		      @if ($loop -> index % 2 == 0) <tr style="background-color:#F3F3F3">
		      @else <tr>
		      @endif
		      <td> {{ $one->id }} </td>
		      <td> {{ $one->title }} </td>
		      </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
  </div>
@endsection