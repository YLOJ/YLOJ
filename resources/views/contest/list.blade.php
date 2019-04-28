@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            {{ $contests -> links() }}
        </div>

        @auth
            @if ( Auth::user()->permission > 0 )
                <div>
                    <form method="post" action="contest/add">
                        @include('buttons.submit' , ['text' => 'Add Contest'])
                        @csrf
                    </form>
                </div> 
                <br>
            @endif
        @endauth

        <div class="row">
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:8%"> ID </th>
                            <th style="width:32%"> Contest Name </th>
                            <th style="width:18%"> Begin Time </th>
                            <th style="width:18%"> End Time </th>
                            <th style="width:12%"> Duration </th>
                            <th style="width:8%"> Rating </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cnt = 1; ?>
                        @foreach ($contests as $contest)
                            @if (($cnt = $cnt + 1) % 2 == 1) 
                                <tr style = "background-color:#F3F3F3">
                            @else 
                                <tr>
                            @endif

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
                                    echo floor($len / 3600).' h ';
                                    if ($len % 3600) echo floor($len % 3600 / 60).' m ';
                                    if ($len % 60) echo ($len % 60).' s ';
                                ?>
                            </td>
                            <?php $x = (($contest -> id ^ 43863) * 4367 + 4385) % 233 - 100; ?>

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
            </div>
        </div>

        <div class="row justify-content-center">
            {{ $contests -> links() }}
        </div>
    </div>
@endsection
