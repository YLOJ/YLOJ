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
      'contests' => $running_contests,
      'title' => 'Running Contests'])
    @endcomponent 

    @component('includes.contest_table', [
      'contests' => $upcoming_contests,
      'title' => 'Upcoming Contests'])
    @endcomponent 

    @component('includes.contest_table', [
      'contests' => $past_contests,
      'title' => 'Past Contests'])
    @endcomponent 
  </div>
@endsection
