<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>All Things Michael Mclean | Admin</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Favicon -->
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">

	<link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
	<link rel="stylesheet" href="{{ asset('css/custom-css.css') }}" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
	@yield('style')

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	@php /*
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	*/ @endphp
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
	<script type="text/javascript" src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plupload/2.3.6/plupload.full.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/1.1.3/metisMenu.min.js"></script>
</head>
<body style="font-family: 'Raleway', sans-serif;">

	<div id="app">
		<div id="wrapper">
			<!-- Navigation -->
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{{ url ('') }}">Songwriter Admin</a>
				</div>
				<!-- /.navbar-header -->

				<ul class="nav navbar-top-links navbar-right">
					<!-- /.dropdown -->
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-user">
							<li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
							</li>
							<li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
									<i class="fa fa-sign-out-alt fa-fw"></i> {{ __('Logout') }}
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>
							</li>
						</ul>
						<!-- /.dropdown-user -->
					</li>
					<!-- /.dropdown -->
				</ul>
				<!-- /.navbar-top-links -->

				<div class="navbar-default sidebar" role="navigation">
					<div class="sidebar-nav navbar-collapse">
						<ul class="nav" id="side-menu">
							<!-- <li class="sidebar-search">
								<div class="input-group custom-search-form">
									<input type="text" class="form-control" placeholder="Search...">
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<i class="fa fa-search"></i>
									</button>
								</span>
								</div>
							</li> -->
							<li {{ (Request::is('/') ? 'class="active"' : '') }}>
								<a href="{{ url ('/dashboard') }}"><i class="fa fa-tachometer-alt fa-fw"></i> Dashboard</a>
							</li>
							<li>
								<a href="#"><i class="fa fa-compact-disc fa-fw"></i> Albums <span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/albums">List</a>
									</li>
									<li>
										<a href="/albums/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-user fa-fw"></i> Artists<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/artists">List</a>
									</li>
									<li>
										<a href="/artists/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-user fa-fw"></i> Authors<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/authors">List</a>
									</li>
									<li>
										<a href="/authors/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-music fa-fw"></i> Instrumentals<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/instrumentals">List</a>
									</li>
									<li>
										<a href="/instrumentals/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-music fa-fw"></i> Podcasts<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/podcasts">List</a>
									</li>
									<li>
										<a href="/podcasts/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-sliders-h fa-fw"></i> Sheet Music<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/sheet_musics">List</a>
									</li>
									<li>
										<a href="/sheet_musics/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-music fa-fw"></i> Songs<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/songs">List</a>
									</li>
									<li>
										<a href="/songs/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-chart-bar fa-fw"></i> Subscriber Metrics<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/subscriber-metrics">Royalty Information</a>
									</li>
									<li>
										<a href="/get-all-subscribers">Subscriber List</a>
									</li>
									<li>
										<a href="/users-cancelled">Cancelled Users</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-video fa-fw"></i> Video<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/videos">List</a>
									</li>
									<li>
										<a href="/videos/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-layer-group fa-fw"></i> Video Category<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="/video-categories">List</a>
									</li>
									<li>
										<a href="/video-categories/create">Create</a>
									</li>
								</ul>
								<!-- /.nav-second-level -->
							</li>

							@php /*
							<li>
								<a href="#"><i class="fa fa-chart-bar fa-fw"></i> Infusionsoft<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="/infusionsoft-settings">Products</a></li>
									<!-- <li><a href="/infusionsoft-settings-promotions">Promotions</a></li> -->
								</ul>
								<!-- /.nav-second-level -->
							</li>
							*/ @endphp


							<li>
								<a href="#"><i class="fa fa-gear fa-fw"></i> Settings <span class="fa arrow"></span> </a>
								<ul class="nav nav-second-level">
									<li><a href="/products">Products</a></li>
									<li><a href="/nmitransactions">NMI</a></li>
									<li><a href="/adminsettings">Trial</a></li>
								</ul>
							</li>

							<li>
								<a href="/loginhistory"><i class="fa fa-gear fa-fw"></i> App Login History <span class="fa arrow"></span> </a>
							</li>
							<li>
								<a href="/appactivitylog"><i class="fa fa-gear fa-fw"></i> App Activity Log <span class="fa arrow"></span> </a>
							</li>

						</ul>
					</div>
					<!-- /.sidebar-collapse -->
				</div>
				<!-- /.navbar-static-side -->
			</nav>

			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">
							<small style="color: #202020;">@yield('page_heading')</small>
						</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<div class="row">
					@yield('section')

				</div>
				<!-- /#page-wrapper -->
			</div>
		</div>
	</div>
	<script src="{{ asset('js/app.js') }}"></script>
	<script type="text/javascript">
		$(function() {
			$('#side-menu').metisMenu();
		});

		//Loads the correct sidebar on window load,
		//collapses the sidebar on window resize.
		// Sets the min-height of #page-wrapper to window size
		$(function() {
				$(window).bind("load resize", function() {
						topOffset = 50;
						width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
						if (width < 768) {
								$('div.navbar-collapse').addClass('collapse');
								topOffset = 100; // 2-row-menu
						} else {
								$('div.navbar-collapse').removeClass('collapse');
						}

						height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
						height = height - topOffset;
						if (height < 1) height = 1;
						if (height > topOffset) {
								$("#page-wrapper").css("min-height", (height) + "px");
						}
				});

				var url = window.location;
				var element = $('ul.nav a').filter(function() {
						return this.href == url || url.href.indexOf(this.href) == 0;
				}).addClass('active').parent().parent().addClass('in').parent();
				if (element.is('li')) {
						element.addClass('active');
				}
		});
	</script>
	{{-- <script src="{{ asset('js/frontend.js') }}" type="text/javascript"></script> --}}
	@yield('script')
</body>
</html>
