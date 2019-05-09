<!DOCTYPE html>
@extends('layouts.app')

<head>
  <link href="http://cdn.bootcss.com/highlight.js/8.0/styles/xcode.min.css" rel="stylesheet">
  <script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
  <script> hljs.initHighlightingOnLoad(); </script>
</head>

@section('content')
  <div class="container">
    <div class="row">
      <div class="col">
        <table class="table table-bordered"> 
          @include('includes.verdict_table')
          <tbody>
            <tr>
              @include('includes.verdict', ['sub' => $sub])
            </tr>
          </tbody>
        </table>

        <div class="accordion">
          @component('includes.collapse_box', ['id' => 'code', 'title' => 'Source Code'])
            <pre><code class="cpp">{{ $sub -> source_code }}</code></pre>
          @endcomponent

          @if($sub -> result == 'Compile Error')	
            @component('includes.collapse_box', ['id' => 'compile_info', 'title' => 'Compile Info'])
              <pre><code class="cpp">{{ $sub -> judge_info }}</code></pre>
            @endcomponent
          @elseif($sub -> result == 'Data Error')
            @component('includes.collapse_box', ['id' => 'error_info', 'title' => 'Error Details'])
              <pre><code class="cpp">{{ $sub -> judge_info }}</code></pre>
            @endcomponent
          @elseif($sub -> result != 'Waiting')
            @component('includes.collapse_box', ['id' => 'details', 'title' => 'Details'])
              @if(isset($sub -> subtask))
                @foreach($sub -> subtask as $task)
                  @component('includes.collapse_box', 
                    ['id' => 'details'.($loop -> index + 1),
                    'title' => 'Subtask '.($loop -> index + 1).': '.$task -> result.' ( Score = '.$task -> score.' )'])
                    @if($task->have_dependency)
                      <table class="table">
                        @if($task->dependency_info['result'] == 'Accepted') 
                          <tr class="table-success text-success"> 
                        @elseif($task->dependency_info['result'] == 'Partially Correct') 
                          <tr class="table-warning" style="color:orange"> 
                        @else 
                          <tr class="table-danger text-danger">
                        @endif
                        <th style="width:17%"> Task Dependency: </th> 
                        <th style="width:23%"> {{ $task->dependency_info['result'] }} </th> 
                        <th style="width:20%"> Score : {{ $task->dependency_info['score'] }} </th>
                        <th style="width:18%"> Time : {{ $task->dependency_info['time_used'] }} ms </th> 
                        <th style="width:22%"> Memory : {{ $task->dependency_info['memory_used'] }}kb  </th>
                          </tr>
                      </table>
                    @endif
                    @component('includes.case_info', ['case_info' => $task -> case_info])
                    @endcomponent
                  @endcomponent
                @endforeach
              @else
                @component('includes.case_info', ['case_info' => $sub -> case_info])
                @endcomponent	
              @endif
            @endcomponent
          @endif
        </div>

        <br>
        @auth
          @if(Auth::user() -> permission > 0)
            @include('buttons.jump-danger', ['href' => url('submission/rejudge/'.$sub -> id), 'text' => 'Rejudge'])
            &nbsp
            @include('buttons.jump-danger', ['href' => url('submission/delete/'.$sub -> id), 'text' => 'Delete'])
          @endif
        @endauth
      </div>
    </div>
  </div>
@endsection
