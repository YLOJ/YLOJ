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

    <form action="/contest/add_submit" method="post">    
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Title</label>
	  <input class="mdui-textfield-input" type="text" name="title"  value="" required/>
	</div>

			<div class="mdui-textfield mdui-textfield-floating-label">
			  <label class="mdui-textfield-label">Time: </label>
			  <input class="mdui-textfield-input inline flatpickr" type="text" name="begin_time" style="width:auto" required>
				~
			  <input class="mdui-textfield-input inline flatpickr" type="text" name="end_time" style="width:auto" required>
 			<label> &nbsp&nbsp  Rule:  &nbsp&nbsp </label>
     <select id="rule" name="rule" class="mdui-select">
          <option value="0" selected> OI </option>
          <option value="1"> IOI </option>
          <option value="2"> ACM </option>
        </select>
  
			</div>


 
		<div class="mdui-textfield ">
		  <label class="mdui-textfield-label">Contest Info</label>
		  <textarea  use_ace="true" ace_language="markdown"  class="mdui-textfield-input" name="contest_info" rows=20></textarea>
		</div>
      @include('buttons.submit' , ['text' => 'Add'])
      @csrf
    </form>
@endsection
