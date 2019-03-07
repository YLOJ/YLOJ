<!DOCTYPE html>
@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">
        {{ $problemset -> links() }}
    </div>

    @auth
    @if ( Auth::user()->permission > 0 )
    <div>
        <form method="post" action="problemset/add">
            <button type="submit" class="btn btn-primary"> Add Problem </button>
            @csrf
        </form>
    </div> <br>
    @endif
    @endauth

    <div class="row">
        <div class="row-md-6 row-md-offset-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:150px">Problem ID</th>
                        <th style="width:900px">Title</th>
                        <th style="width:100px">Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($problemset as $problem)
                    @if ($problem -> id % 2 == 1) <tr style="background-color:#F3F3F3">
                        @else
                    <tr>
                        @endif
                        <td> {{ $problem -> id }} </td>
<<<<<<< HEAD
                        <td> <a href="/problem/{{$problem->id}}"> {{$problem->title}} </a> </td>
                        <?php
						$x = (($problem -> id ^ 43863) * 4367 + 4385) % 233 - 100;
					?>
=======
                        <td> <a href="/problem/{{ $problem->id }}"> {{$problem->title}} </a> </td>
                        <?php $x = (($problem -> id ^ 43863) * 4367 + 4385) % 233 - 100; ?>

>>>>>>> 24f7a377843c5645fb43364d24f0b31fbb81fd12
                        @if ($x > 0)
                        <td class="text-success"> <b> {{ $x }} </b> </td>
                        @elseif ($x == 0)
                        <td class="text-muted"> <b> {{ $x }} </b> </td>
                        @else
                        <td class="text-danger"> <b> {{ $x }} </b> </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-center">
        {{ $problemset -> links() }}
    </div>

</div>

@endsection