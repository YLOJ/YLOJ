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
	<h2> <a href="/contest/{{$contest->id}}">Contest {{$contest->id}}</h2>
    @include('buttons.jump', ['href' => url('/contest/edit/problemset/'.$contest->id) , 'text' => '编辑比赛题目'])
    <form action="/contest/edit_submit/{{ $contest -> id }}" method="post">    
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="{{ $contest -> title }}" class="form-control">
      </div>
      <div class="form-inline">
        <label> From &nbsp </label>
        <input type="text" name="begin_time" value="{{ $contest -> begin_time }}"class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp To &nbsp </label>
        <input type="text" name="end_time" value="{{ $contest -> end_time }}"class="flatpickr form-control bg-white" placeholder="Pick date and time">
        <label> &nbsp&nbsp Rule &nbsp </label>
        <select id="rule" name="rule" class="form-control">
          <option value="0"> OI </option>
          <option value="1"> IOI </option>
        </select>
	    <script type="text/javascript">
			onload = function() {
				document.getElementById("rule").selectedIndex = {{$contest -> rule}};
   			}
   		</script>

      </div>
      <div class="form-group">
        <label> Contest Info </label> <br>
        <textarea rows="20" name="contest_info" class="form-control"> {{ $contest -> contest_info }} </textarea>
      </div>
      <div class="form-check">
        <label>
        	公开性	
        </label>
		<br>
 		<input type="radio" name="visibility" value=2 {{$contest->visibility==2?'checked':''}}/>隐藏
		<br>
 		<input type="radio" name="visibility" value=1 {{$contest->visibility==1?'checked':''}}/>权限
		<br>
 		<input type="radio" name="visibility" value=0 {{!$contest->visibility?'checked':''}}/>默认

      </div>

      @include('buttons.submit' , ['text' => 'Save'])
      @csrf
    </form>
  </div>
@endsection
