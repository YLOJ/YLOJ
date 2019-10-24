<!DOCTYPE html>
@extends('layouts.app')

@section('content')
  <div class="container">
    <div style="text-align:center"> 
      <br> <br>
      <h2> Statistics<a href="{{url('/problem/'.$id)}}"> #{{$id}}: {{$title}} </a> </h2>
      <br> <br>
    </div>
	<div id="statistics" class="mdui-tab">
	  <a href="#fastest" class="mdui-ripple" id="select-fastest">最快</a>
	  <a href="#shortest" class="mdui-ripple" id="select-shortest">最短</a>

	</div>
	<div id="fastest" class="mdui-p-a-2">
	    <div class="row">
	      <div class="col">
	
	        <table class="table table-bordered">
	          @include('includes.verdict_table', ['first_column' => 'Rank']) 
	          <tbody>
	            @foreach ($fastest as $sub)
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
	</div>
	<div id="shortest" class="mdui-p-a-2">
	    <div class="row">
	      <div class="col">
	
	        <table class="table table-bordered">
	          @include('includes.verdict_table', ['first_column' => 'Rank']) 
	          <tbody>
	            @foreach ($shortest as $sub)
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
	</div>
	<script>
	var $$=mdui.JQ;	
	console.log(document.cookie);
	console.log(document.cookie.indexOf("show-shortest="));
	var tab=new mdui.Tab('#statistics');
	if(document.cookie.indexOf("show-shortest=")>-1)
		tab.show('select-shortest');
	else 
		tab.show('select-fastest');

	mdui.mutation();
	$$("#select-fastest").on("show.mdui.tab",function (){
		var d = new Date();
		d.setTime(d.getTime()-1);
		document.cookie = 'show-shortest=1;path=/;'+"expires="+d.toGMTString();
	});
	$$("#select-shortest").on("show.mdui.tab",function (){

		document.cookie = 'show-shortest=1;path=/';
	});
	</script>


    <div class="row justify-content-center">
      {{ $fastest -> links() }}
    </div>
  </div>
@endsection 
