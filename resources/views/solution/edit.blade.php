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
    <h2><a href="{{url('/problem/solution/'.$id)}}">Problem #{{$id}} Solution</a> </h2>

    @include('buttons.jump', ['href' => url('/problem/solution/upload/'.$id) , 'text' => 'Upload Files'])

    @include('buttons.jump', ['href' => url('/problem/edit/'.$id) , 'text' => 'Edit Problem'])
	<br>
    <form action="/problem/solution/edit_submit/{{$id}}" method="post">
	<div class="mdui-textfield mdui-textfield-floating-label">
	  <label class="mdui-textfield-label">Content</label>
	  <textarea id="content_md" style="display:none" class="mdui-textfield-input" type="text" rows=20 name="content_md"> {{$content_md}}</textarea>
		<div id="content_md_edit" style="height: 50vh">{{$content_md}}</div>
	</div>
<script src="/js/ace.js/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
	function update_editor( editor ) {
//		console.log( editor.getValue() );
		document.getElementById('content_md').value = editor.getValue();
	}
    var editor = ace.edit("content_md_edit");
    editor.setTheme("ace/theme/github");
    editor.session.setMode("ace/mode/markdown");
	editor.setOption( 'printMargin', false );
	document.getElementById('content_md').value = editor.getValue();
	editor.session.on('change', function( delta ) { update_editor( editor ); });
</script>

      <br>
      @include('buttons.submit',['text' => 'Save'])
      @csrf
    </form>
@endsection
