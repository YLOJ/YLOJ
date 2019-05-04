<div class="card" >
  <div class="card-header" style="padding:5px 7px 2px;" id="heading{{ $id }}">
    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $id }}" aria-expanded="true" aria-controls="collapse{{ $id }}">
      <a style="text-bg"> <b> {{ $title }} </b> </a>
    </button>
  </div>

  <div id="collapse{{ $id }}" class="collapse show" aria-labelledby="heading{{ $id }}">
    <div class="card-body" style="padding:15px 10px 0px;">
      {{ $slot }}	
    </div>
  </div>
</div>
