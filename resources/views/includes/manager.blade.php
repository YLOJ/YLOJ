<table class="table table-bordered">
  <thead>
    <tr>
      <th style="">当前管理员</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($manager as $one)
      @if ($loop -> index % 2 == 0) <tr style="background-color:#F3F3F3">
      @else <tr>
      @endif
      <td> {{ $one }} </td>
      </tr>
    @endforeach
  </tbody>
</table>
