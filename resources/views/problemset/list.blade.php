<!DOCTYPE html>
@extends('layouts.app')

@section('content')
    <div style="text-align:center"> 
      {{ $problemset -> links() }}
    </div>

    @auth
      @if ( Auth::user()->permission > 1 )
        <div>
        @include('buttons.jump' , ['text' => 'Add Problem','href'=>'/problem/add'])
        </div> 
      @endif
    @endauth

    <br>
    <?php
      $links = $problemset -> links();
    ?>

    @component('includes.problem_table', ['problemset' => $problemset])
    @endcomponent 

    <div style="text-align:center"> 
      {{ $links }}
    </div>
@endsection
