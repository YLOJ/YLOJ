@extends("layouts.app")

@section("content")
<script src="/js/ace.js/ace.js" type="text/javascript" charset="utf-8"></script>
        <div class="text-center">
          <h1> {{ $title }} </h1>
			<?php
				echo $head;
			?>
          <div class="mdui-btn-group">
			<button id="submit" class="mdui-btn mdui-color-theme" onclick="toggle()">
		    <img src="{{ asset('svg/icons/paper-plane.ico') }}" class="icon-sm"/>  Submit
			</button>
			<button id="back" class="mdui-btn mdui-color-theme" onclick="toggle()" style="display:none">
		    <img src="{{ asset('svg/icons/paper-plane.ico') }}" class="icon-sm"/>  Back
			</button>

            @include('buttons.jump-icon' , ['href' => url(($contest_id?'/contest/'.$contest_id:'').'/submission?problem_id='.$id) , 'icon' => 'text-left' , 'text' => 'Submissions'])
			@if($contest_ended)
            @include('buttons.jump-icon' , ['href' => url('/problem/statistics/'.$id) , 'icon' => 'statistics' , 'text' => 'Statistics'])
			@endif
            @include('buttons.jump-icon' , ['href' => url('/problem/customtests/') , 'icon' => 'test-file' , 'text' => 'Custom tests'])
			@if($contest_ended)
            @include('buttons.jump-icon' , ['href' => url('/problem/solution/'.$id) , 'icon' => 'test-file' , 'text' => 'Solution'])
			@endif
			@if($contest_id)
            @include('buttons.jump-icon' , ['href' => url('/contest/'.$contest_id) , 'icon' => 'paper-plane' , 'text' => 'Contest Index'])
			@endif
            @if ($is_admin)
				<a class="mdui-btn mdui-color-theme-accent" href="/problem/edit/{{$id}}">
                <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </a>
        	@endif
          </div>
        </div>

        <br>
		<div class="mdui-card mdui-hoverable" id="content">
		<div class="mdui-card-content">
			<?php
				echo $content;?>
			</div>
		</div>

		<div id="submit-code" style="display:none"  class="mdui-card mdui-hoverable">
			<div class="mdui-card-content">
		   		<form action="/problem/submit" method="post">
					@csrf
					<input type="hidden" name="pid" value={{$id}}>
					@if($contest_id)
						<input type="hidden" name="cid" value={{$contest_id}}>
					@endif
					<div class="mdui-textfield mdui-textfield-floating-label">
					<label class="mdui-textfield-label">Code</label>
					<textarea class="mdui-textfield-input" id="source_code" style="display:none" type="text" rows=20 name="source_code"></textarea>
					<div id="source_code_edit" style="height: 50vh"></div>
					</div>
	      			@include('buttons.submit' , ['text' => 'Submit'])
			    </form>
			</div>
		</div>
		<script>
			function update_editor( editor ) {
				document.getElementById('source_code').value = editor.getValue();
			}
			var source_code_edit = ace.edit("source_code_edit");
			source_code_edit.setTheme("ace/theme/gitub");
			source_code_edit.session.setMode("ace/mode/c_cpp");
			source_code_edit.setOption( 'printMargin', false );
			document.getElementById('source_code').value = source_code_edit.getValue();
			source_code_edit.session.on('change', function( delta ) { update_editor( source_code_edit ); });
		</script>

		<script>
			function toggle(){
				$("#content").toggle();
				$("#submit-code").toggle();
				$("#submit").toggle();
				$("#back").toggle();
			}
		</script>


@endsection 
