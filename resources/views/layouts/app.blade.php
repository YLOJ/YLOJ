<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	    <!-- CSRF Token -->
	    <meta name="csrf-token" content="{{ csrf_token() }}">
    	<title>{{ config('app.name', 'YLOJ') }}</title>

    	<script src="{{ asset('js/app.js') }}" defer></script>
    	<script src="{{ asset('js/color-converter.min.js') }}" defer></script>
    	<script src="{{ asset('js/yloj.js') }}" defer></script>
    	<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>

    <script type="text/javascript" async
            src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>


   <link href="{{ asset('css/style.css') }}" rel="stylesheet">
   <!--	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-grid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">--!>
		<link rel="stylesheet" href="/mdui/css/mdui.min.css"/>
		<script src="/mdui/js/mdui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <link href="http://cdn.bootcss.com/highlight.js/8.0/styles/xcode.min.css" rel="stylesheet">
  <script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
  <script> hljs.initHighlightingOnLoad(); </script>

	</head>
	<body class="mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-light-blue mdui-theme-accent-blue mdui-loaded">
		<div class="mdui-appbar mdui-appbar-fixed">
			<div class="mdui-toolbar mdui-color-theme  ">
				<span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white " mdui-drawer="{target: '#main-drawer', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
				<a class="mdui-btn mdui-ripple  " href="/"><span class="mdui-typo-title">{{ config('app.name', 'YLOJ') }}</span></a>
				<div class="mdui-toolbar-spacer"></div>
			@guest
				<a class="mdui-typo-title top-btn mdui-ripple  " href="{{ route('login') }}">Login</a>
				<a class="mdui-typo-title top-btn mdui-ripple  " href="{{ route('register') }}">Register</a>
			@else

				<a class="mdui-typo-title top-btn mdui-ripple  " mdui-menu="{target: '#example-1',covered: false}">{{ Auth::user()->name }}</a>
<!--   -->
			  <ul class="mdui-menu " id="example-1">
			    <li class="mdui-menu-item">
			      <a href="/user/profile" class="mdui-ripple">Profile</a>
			    </li>

				@if (Auth::user()->permission > 1)
			    <li class="mdui-menu-item">
			      <a href="/webadmin" class="mdui-ripple">WebAdmin</a>
			    </li>
				@endif
			  </ul>


				<a class="mdui-typo-title top-btn mdui-ripple  " href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
			@endguest
			</div>
		</div>
		<div class="mdui-drawer mdui-color-white" id="main-drawer">
			<ul class="mdui-list">
			  <a href="{{ route('contest.index') }}"><li class="mdui-list-item mdui-ripple">比赛</li></a>
			  <a href="{{ route('problem.index') }}"><li class="mdui-list-item mdui-ripple">题库</li></a>
			  <a href="{{ route('submission') }}"><li class="mdui-list-item mdui-ripple">提交记录</li></a>
              <a href="{{ route('help') }}"><li class="mdui-list-item mdui-ripple">帮助</li></a>
			</ul>
		</div>
		<div class="mdui-container mdui-typo"> 
      		@yield('content')
		</div>
    <script type="text/javascript">
      document.getElementsByClassName("flatpickr").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
      });
    </script>

	</body>
</html>
