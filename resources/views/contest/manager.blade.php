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
    <h3><a href="{{url('/contest/'.$id)}}">Contest #{{$id}}</a> </h3>

    @include('buttons.jump', ['href' => url('/contest/edit/'.$id) , 'text' => '回到编辑比赛页面'])
	<p>
	用法：

	每行+username 或者 -username 表示 加入这个用户或者减掉这个用户
	</p>
	<div id='contest_manager_update' style='vertical-align:top;display:inline-block;width:49%'>
    <form action="/contest/update_manager/{{$id}}" method="post">
      <div class="form-group">
        <textarea rows="16" name="content" class="form-control"></textarea>
      </div>
      <br>
      @include('buttons.submit',['text' => '更新'])
      @csrf
    </form>
	</div>
	<div id='contest_manager_table' style='vertical-align:top;display:inline-block;width:49%'>
	@include('includes.manager',['manager'=>$manager])
	</div>
  </div>
@endsection
