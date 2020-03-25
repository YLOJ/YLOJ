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
    	<script src="{{ asset('js/ace.js/ace.js') }}" defer></script>
    	<script src="{{ asset('js/yloj.js?v=20191225') }}" defer></script>
    	<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>

    <script type="text/javascript" async
            src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>


   <link href="{{ asset('css/style.css?v=20200325') }}" rel="stylesheet">

   <!--	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-grid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">--!>
		<link rel="stylesheet" href="/mdui/css/mdui.min.css"/>
		<script src="/mdui/js/mdui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="/js/prism.js"></script>
    <link rel="stylesheet" href="/css/prism.css">
	</head>
	<body class="mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-light-blue mdui-theme-accent-blue mdui-loaded line-numbers">

		<div class="mdui-appbar mdui-appbar-fixed">
			<div class="mdui-toolbar mdui-color-theme  ">
				<span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white " mdui-drawer="{target: '#main-drawer', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
				<a class="mdui-btn mdui-ripple  " href="/"><span class="mdui-typo-title">{{ config('app.name', 'YLOJ') }}</span></a>
				<div class="mdui-toolbar-spacer"></div>
			@guest
				<a class="mdui-btn mdui-ripple  " href="{{route('login')}}"><span class="mdui-typo-title">Login</span></a>
				<a class="mdui-btn mdui-ripple  " href="{{route('register')}}"><span class="mdui-typo-title">Register</span></a>
			@else

				<a class="mdui-btn top-btn mdui-ripple" mdui-menu="{target: '#example-1',covered: false}"><span class="mdui-typo-title">{{ Auth::user()->name }}</span></a>
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


				<a class="mdui-btn top-btn mdui-ripple  " href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
									<span class="mdui-typo-title">{{ __('Logout') }}</span></a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
			@endguest
			</div>
		</div>
		<div class="mdui-drawer" id="main-drawer" style="background-color:#D0D0D0">
			<ul class="mdui-list">
			  <a href="{{ route('contest.index') }}" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-blue">date_range</i><div class="mdui-list-item-content">比赛</div></a>
			  <a href="{{ route('problem.index') }}" class="mdui-list-item mdui-ripple">
<i class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-blue">book</i><div class="mdui-list-item-content">题库</div></a>
			  <a href="{{ route('submission') }}" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-blue">format_list_bulleted</i><div class="mdui-list-item-content">提交记录</div></a>
			  <a href="{{ route('help') }}" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-blue">help</i><div class="mdui-list-item-content">帮助
</div></a>
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
