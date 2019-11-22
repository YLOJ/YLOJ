@extends('layouts.app')

@section('content')
    @auth
      @if ( Auth::user()->permission > 1 )
         @include('buttons.jump' , ['text' => 'Add Contest','href'=>"/contest/add"])
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
@endsection
