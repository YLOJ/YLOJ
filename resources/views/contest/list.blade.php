@extends('layouts.app')

@section('content')
  <div class="container">

    @auth
      @if ( Auth::user()->permission > 0 )
        <div>
          <form method="post" action="contest/add">
            @include('buttons.submit' , ['text' => 'Add Contest'])
            @csrf
          </form>
        </div> 
        <br>
      @endif
    @endauth

	<h> Present Contests</h>
    <div class="row">
      <div class="col">
		  @component('includes.contest_table', ['contests' => DB::table('contest') -> where('begin_time', '<=', now())->where('end_time', '>', now())->paginate(20)])
        @endcomponent 
      </div>
    </div>

	<h> Future Contests</h>
    <div class="row">
      <div class="col">
		  @component('includes.contest_table', ['contests' => DB::table('contest') -> where('begin_time', '>', now())->paginate(20)])
        @endcomponent 
      </div>
    </div>

	<h> Past Contests</h>
    <div class="row">
      <div class="col">
		  @component('includes.contest_table', ['contests' => DB::table('contest') -> where('end_time', '<=', now())->paginate(20)])
        @endcomponent 
      </div>
    </div>

  </div>

@endsection
