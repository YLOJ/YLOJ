<table class="table table-bordered">
  <thead>
    <tr>
      <th style="width:13%">Problem ID</th>
      <th style="width:78%">Title</th>
      <th style="width:9%">Rating</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problemset as $problem)
      @if ($loop -> index % 2 == 0) <tr style="background-color:#F3F3F3">
      @else <tr>
      @endif
      <td> {{ $problem -> id }} </td>
      <td> <a href="/problem/{{ $problem->id }}"> {{$problem->title}} </a> </td>
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
