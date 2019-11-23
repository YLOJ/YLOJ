@extends('layouts.app')

@section('content')
  <div class="container">
    <form action="" method="post">
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Source Code</label>
	  <textarea class="mdui-textfield-input" type="text" rows=20 name="code"> {{$code}}</textarea>
	</div>
	<div>
	  <div class="mdui-textfield mdui-textfield-floating-label" style="float:left;width:50%">
	  	<label class="mdui-textfield-label">Input Text File</label>
	  	<textarea class="mdui-textfield-input" type="text" rows=5 name="input"> {{$input}}</textarea>
	  </div>
	  <div class="mdui-textfield mdui-textfield-floating-label {{isset($error)?'mdui-textfield-invalid':''}}" style="float:left;width:50%">
	  	<label class="mdui-textfield-label">Output Text File</label>
	  	<textarea class="mdui-textfield-input" type="text" rows=5 name="output" id="output"> {{$output}}</textarea>
		@if(isset($error))
  			<div class="mdui-textfield-error">{{$error}}</div>
		@endif
	  </div>
	 </div>
     @include('buttons.submit' , ['text' => 'Submit'])

     @csrf
    </form>
<script src=/js/app.js></script>
<script>
Echo.channel('Submission')
.listen('.submission.custom_test', (e) => {
	xsub=e.message;
	console.log(xsub);
	if(xsub['id']=="{{$id}}"){
		$('#output').val(
			xsub['output']
		);
	}
});
</script>
  </div>
@endsection
