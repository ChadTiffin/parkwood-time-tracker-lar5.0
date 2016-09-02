<header>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="wrapper">
			<img src="{{URL::to('/')}}/images/logo.png" class="logo">
			<div class="title">Time Tracker
				<div class="greeting">
					@if (Auth::check())

						Hello, {{Auth::user()->first_name}} {{Auth::user()->last_name}}

					@endif
				</div>
			</div>

			<span class="glyphicon glyphicon-menu-hamburger mobile-menu pull-right" aria-hidden="true"></span>

			<div class=" pull-right">
				
				@if (Auth::check())

				<form action="{{action('HomeController@punchClock')}}" method="post" id="punch-clock">

					<input type='submit' value='Clock {{$header_data['clock_direction']}}' class="btn {{$header_data['clock_btn_type']}}">

				</form>

				<ul class="nav navbar-nav">
					<li><a href="{{ action('HomeController@showSummary') }}/">Summary</a></li>
					<li><a href="{{ action('HomeController@showLogs') }}/{{ Auth::user()->id }}/{{date('Y')}}-01-01/{{date('Y-m-d')}}">Logs</a></li>
					<li><a href="{{ action('SettingsController@showSettings') }}">Settings</a></li>
					<li><a href="{{ action('UsersController@showUsers') }}">Users</a></li>
					<li><a href="{{ action('HomeController@logout') }}">Logout</a></li>
				</ul>
				@endif
			</ul>
			@if (Auth::check())
			<p class="clock-status">{{$header_data['status']}}</p>
			@endif

		</div>
	</nav>

</header>