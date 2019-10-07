@extends('layouts.app')

@section('content')
  <div class="container">
    <form action="" method="post">
      <div class="form-group">
        <label>Source Code</label> <br>
        <textarea rows="15" name="code" , class="form-control">{{$code}}</textarea>
      </div>
	  <div>
      <div class="form-group" style="width:50%;overflow:hidden;float:left">
        <label>Input Text File</label> <br>
        <textarea rows="5" name="input" , class="form-control">{{$input}}</textarea>
      </div>
      <div class="form-group" style="width:50%;overflow:hidden;float:left">
		<label>Output Text File</label> 
		<?php
		if(isset($error))
			 echo "<span style=color:red>*".$error."</span>";
		?>
<br>
        <textarea rows="5" name="output" id="output" class="form-control">{{$output}}</textarea>
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
