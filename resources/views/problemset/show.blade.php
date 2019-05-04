@extends("layouts.app")

@section("content")
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="text-center">
          <h1> #{{$id}}. {{ $title }} </h1>
          Time Limit : {{ $time_limit }} Ms <br>
          Memory Limit : {{ $memory_limit }} Mb <br> <br>

          <div class="btn-group-md">
            @include('buttons.jump-icon' , ['href' => url('/problem/submit/'.$id) , 'icon' => 'paper-plane' , 'text' => 'Submit'])
            @include('buttons.jump-icon' , ['href' => url('/submission?problem_id='.$id) , 'icon' => 'text-left' , 'text' => 'Submissions'])
            @include('buttons.jump-icon' , ['href' => url('/problem/statistics/'.$id) , 'icon' => 'statistics' , 'text' => 'Statistics'])
            @include('buttons.jump-icon' , ['href' => url('/problem/customtests/') , 'icon' => 'test-file' , 'text' => 'Custom tests'])

            @auth
              @if ( Auth::user()->permission > 0)
                <button class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="document.getElementById('myform').submit();">
                  <img src="{{ asset('svg/icons/edit.ico') }}" class="icon-sm"/> Edit </button>
                <form id="myform" method="post" action="/problem/edit/{{$id}}">
                  @csrf
                </form>
              @endif
            @endauth
          </div>
        </div>

        <br>
        <?php echo $content_html ?>

      </div>
    </div>
  </div>
@endsection 
