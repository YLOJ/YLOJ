<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YLOJ') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/tablesorter.min.js') }}" defer></script>
    <script src="{{ asset('js/tablesorter.widgets.min.js') }}" defer></script>
    <script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>

    <script type="text/javascript" async
            src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-grid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Datetime Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <link href="http://cdn.bootcss.com/highlight.js/8.0/styles/xcode.min.css" rel="stylesheet">
  <script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
  <script> hljs.initHighlightingOnLoad(); </script>
  </head>
  <body>
    <div id="app">
      <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
              {{ config('app.name', 'YLOJ') }}
            </a>
          </div>

          <div class="collapse navbar-collapse" id="navbar-menu">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('contest.index') }}"> Contests </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('problem.index') }}">{{ __('Problemset') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('submission') }}">{{ __('Submission') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('help') }}">{{ __('Help') }}</a>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Authentication Links -->
              @guest
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                  </li>
                @endif
              @else
                <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                  </a>

                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
					@if (Auth::user()->permission > 1)
                    <a class="dropdown-item" href="/webadmin">WebAdmin</a>
					@endif
                    <a class="dropdown-item" href="{{ route('logout') }}"
                                             onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                  </div>
                </li>
              @endguest
            </ul>
          </div>
        </div>
      </nav>
    </div>

    <main class="py-4">
      @yield('content')
    </main>

    <script type="text/javascript">
      document.getElementsByClassName("flatpickr").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
      });
    </script>
  </body>
</html>
