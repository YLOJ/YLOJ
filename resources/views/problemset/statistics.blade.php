<!DOCTYPE html>
@extends('layouts.app')

@section('content')

<div class="container">

	<div style="text-align:center"> 
		<br> <br>
		<h2> Statistics<a href="{{url('/problem/'.$id)}}"> #{{$id}}: {{$title}} </a> </h2>
		<br> <br>
	</div>

    <div class="row">
        <div class="col">

            <table class="table table-bordered">
				@include('includes.verdict_table', ['first_column' => 'Rank']) 
                <tbody>
                    @foreach ($submissionset as $sub)
                    @if ($sub -> id % 2 == 0)
                    <tr style="background-color:#F3F3F3">
                        @else
                    <tr>
                        @endif
						@include('includes.verdict', ['sub' => $sub])
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-center">
        {{ $submissionset -> links() }}
    </div>

</div>

@endsection 
