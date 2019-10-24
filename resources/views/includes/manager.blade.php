<table class="mdui-table">
  <thead>
    <tr>
      <th>当前管理员</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($manager as $one)
      <tr>
      <td> {{ $one }} </td>
      </tr>
    @endforeach
  </tbody>
</table>
