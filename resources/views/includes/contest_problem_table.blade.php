
<table class="mdui-table mdui-table-hoverable mdui-hoverable">

  <thead>
    <tr>
      <th style="width:5%;text-align: center">#</th>
      <th style="width:85%;text-align: center">Title</th>
      <th style="width:10%;text-align:center">Rating</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problemset as $problem)
      <tr>
      <td style="text-align:center"> {{ $problem -> id }} </td>
      <td style="text-align:center"> <a href="/problem/{{ $problem->id }}"> {{$problem->title}} </a> </td>
      <?php $x = (($problem -> id ^ 43863) * 4367 + 4385) % 233 - 100; ?>

      @if ($x > 0)
        <td class="text-success" style="text-align:center"> <b> {{ $x }} </b> </td>
      @elseif ($x == 0)
        <td class="text-muted"  style="text-align:center"> <b> {{ $x }} </b> </td>
      @else
        <td class="text-danger"  style="text-align:center"> <b> {{ $x }} </b> </td>
      @endif
      </tr>
    @endforeach
  </tbody>
</table>
