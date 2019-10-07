@extends("layouts.app")

@section("content")
  <div class="container">
	<div class="content"></div>
		<script src=/js/app.js></script>
		<script>
			md=@json($content);

			$('.content').html(marked(md));
		</script>

  </div>
@endsection 
