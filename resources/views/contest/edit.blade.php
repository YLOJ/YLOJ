@extends('layouts.app')

@section('content')
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
	<h2> <a href="/contest/{{$contest->id}}">Contest #{{$contest->id}}</h2>
    @include('buttons.jump', ['href' => url('/contest/edit/problemset/'.$contest->id) , 'text' => '编辑比赛题目'])
    @include('buttons.jump', ['href' => url('/contest/edit/manager/'.$contest->id) , 'text' => '编辑比赛管理员'])
    <form action="/contest/edit_submit/{{ $contest -> id }}" method="post">    
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Title</label>
	  <input class="mdui-textfield-input" type="text" name="title"  value="{{$contest->title}}" required/>
	</div>
			<div class="mdui-textfield mdui-textfield-floating-label">
			  <label class="mdui-textfield-label">Time: </label>
			  <input class="mdui-textfield-input inline flatpickr" type="text" name="begin_time" style="width:auto"  value="{{ $contest -> begin_time }}" required>
				~
			  <input class="mdui-textfield-input inline flatpickr" type="text" name="end_time" style="width:auto"   value="{{ $contest -> end_time }}" required>
 			<label> &nbsp&nbsp  Rule:  &nbsp&nbsp </label>
     <select id="rule" name="rule" class="mdui-select">
          <option value="0" selected> OI </option>
          <option value="1"> IOI </option>
          <option value="2"> ACM </option>
        </select>
 	    <script type="text/javascript">
			onload = function() {
				document.getElementById("rule").selectedIndex = {{$contest -> rule}};
   			}
   		</script>
 
			</div>

		<div class="mdui-textfield mdui-textfield-floating-label">
		  <label class="mdui-textfield-label">Contest Info</label>
		  <textarea class="mdui-textfield-input" name="contest_info" rows=20>{{$contest->contest_info}}</textarea>
		</div>

    <div class="form-check">
      <label>
     	公开性	
       </label>
	<br>
	<label class="mdui-radio">
	  <input type="radio" name="visibility" value=2 {{$contest->visibility==2?'checked':''}}/>
	  <i class="mdui-radio-icon"></i>隐藏
	</label>
	<label class="mdui-radio">
	  <input type="radio" name="visibility" value=1 {{$contest->visibility==1?'checked':''}}/>
	  <i class="mdui-radio-icon"></i>权限
	</label>
	<label class="mdui-radio">
	  <input type="radio" name="visibility" value=0 {{$contest->visibility==0?'checked':''}}/>
	  <i class="mdui-radio-icon"></i>默认
	</label>
    </div>
      @include('buttons.submit' , ['text' => 'Save'])
      @csrf
   </form>


@endsection
