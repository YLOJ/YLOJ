
<table class="mdui-table mdui-table-hoverable mdui-hoverable">
  <thead>
    <tr>
      <th style="width:13%">Problem ID</th>
      <th style="width:78%">Title</th>
      <th style="width:9%">Rating</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problemset as $problem)
      <tr>
      <td> {{ $problem -> id }} </td>
      <td> <a href="/problem/{{ $problem->id }}?contest_id={{$cid}}"> {{$problem->title}} </a> </td>
      <?php $x = (($problem -> id ^ 43863) * 4367 + 4385) % 233 - 100; ?>

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
