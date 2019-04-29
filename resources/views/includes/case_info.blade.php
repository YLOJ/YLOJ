<table class="table">
	<tbody>
		@foreach($case_info as $info)
			@if($info['result'] == 'Accepted') <tr class="table-success text-success"> 
			@elseif($info['result'] == 'Partially Correct') <tr class="table-warning text-danger"> 
			@else <tr class="table-danger text-danger">
			@endif
				<th style="width:15%"> Case {{ $loop -> index + 1 }}: </th> 
				<th style="width:25%"> {{ $info['result'] }} </th> 
				<th style="width:20%"> Score : {{ $info['score'] }} </th>
				<th style="width:18%"> Time : {{ $info['time_used'] }} ms </th> 
				<th style="width:22%"> Memory : {{ $info['memory_used'] }}kb  </th>
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
