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

	@component('includes.contest_table', [
		'contests' => DB::table('contest') -> where('begin_time', '<=', now()) -> where('end_time', '>', now()) -> paginate(1000),
		'title' => 'Running Contests'])
    @endcomponent 

	@component('includes.contest_table', [
		'contests' => DB::table('contest') -> where('begin_time', '>', now()) -> orderby('begin_time', 'asc') -> paginate(1000),
		'title' => 'Upcoming Contests'])
    @endcomponent 

	@component('includes.contest_table', [
		'contests' => DB::table('contest') -> where('end_time', '<=', now()) -> orderby('end_time', 'desc') -> paginate(20),
		'title' => 'Past Contests'])
    @endcomponent 

  </div>

@endsection
