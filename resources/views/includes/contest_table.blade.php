@if($contests -> count() > 0)
	<h3> {{ $title }} </h3>
	<table class="mdui-table mdui-table-hoverable mdui-hoverable">
		<thead>
			<tr>
				<th style="width:8%"> ID </th>
				<th style="width:32%"> Contest Name </th>
				<th style="width:18%"> Begin Time </th>
				<th style="width:18%"> End Time </th>
				<th style="width:12%"> Duration </th>
				<th style="width:8%"> Rule </th>
			</tr>
		</thead>
		<tbody>
			@foreach ($contests as $contest)
				<tr>

				<td> {{ $contest -> id }} </td>
				<td> 
					<a href="/contest/{{ $contest -> id }}">  
						{{ $contest -> title }} 
					</a> 
				</td>
				<td> {{ $contest -> begin_time }} </td>
				<td> {{ $contest -> end_time }} </td>
				<td> 
					<?php
						$len = strtotime($contest -> end_time) - strtotime($contest -> begin_time);
						$str = sprintf("%02d:%02d:%02d", floor($len / 3600), floor($len % 3600 / 60), $len % 60);
						echo $str;
					?>
				</td>

				@if($contest->rule==0)
	            	<td class="text-success"> <b> OI </b> </td>
				@elseif($contest->rule==1)
	            	<td class="text-success"> <b> IOI </b> </td>
				@elseif($contest->rule==2)
	            	<td class="text-success"> <b> ACM </b> </td>
				@endif
					</tr>
				@endforeach
		</tbody>
	</table>
	<div style="text-align: center">
		{{ $contests -> links() }}
	</div>
@endif
