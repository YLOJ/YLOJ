<h1> id = {{ $id }} </h1>
val = {{ $y }} <br>

@for ($i = 0; $i <= $id; $i++)
<h3> {{ $i * $i }} </h3>
@endfor
