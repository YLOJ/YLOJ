<div class="card">
	<div class="card-header" id="heading{{ $id }}">
		<h5 class="mb-0">
			<button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $id }}" aria-expanded="true" aria-controls="collapse{{ $id }}">
				<h5> {{ $title }} </h5>
			</button>
		</h5>
	</div>

	<div id="collapse{{ $id }}" class="collapse show" aria-labelledby="heading{{ $id }}">
		<div class="card-body">
			{{ $slot }}	
		</div>
	</div>
</div>
