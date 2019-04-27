<table class="table">
	<tbody>
		@foreach($case_info as $info)
			@if($info['result'] == 'Accepted') <tr class="table-success text-success"> 
			@elseif($info['result'] == 'Partially Correct') <tr class="table-warning text-danger"> 
			@else <tr class="table-danger text-danger">
			@endif
				<th> Case {{ $loop -> index + 1 }}: </th> 
				<th> {{ $info['result'] }} </th> 
				<th> Score : {{ $info['score'] }} </th>
				<th> Time : {{ $info['time_used'] }} ms </th> 
				<th> Memory : {{ $info['memory_used'] }}kb  </th>
			</tr>
		@endforeach
	</tbody>
</table>

<!--
	<ul class="list-group">
		@foreach($case_info as $info)
			@if($info['result'] == 'Accepted') <li class="list-group-item list-group-item-success"> 
			@elseif($info['result'] == 'Partially Correct') <li class="list-group-item list-group-item-warning"> 
			@else <li class="list-group-item list-group-item-danger">
			@endif
			Case {{ $loop -> index + 1 }} : {{ $info['result'] }} &nbsp &nbsp Score : {{ $info['score'] }} &nbsp &nbsp Time : {{ $info['time_used'] }} ms &nbsp &nbsp Memory : {{ $info['memory_used'] }}kb  
			</li>
		@endforeach
	</ul>
--!>
