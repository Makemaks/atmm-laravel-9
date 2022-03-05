<!doctype html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | All Things Michael Mclean</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Favicon -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
        <link href="{{ asset('css/custom-css-2.css') }}" rel="stylesheet">
        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" rel="stylesheet">
        <style>

        </style>
        @auth
        @if(!Request::is('feedbacks') && !Request::is('settings'))
        <link rel="stylesheet" href="{{ asset('css/header_style.css') }}" rel="stylesheet">
        @endif
        @endauth

        @yield('style')
        <script type="text/javascript" src="{{ asset('js/jquery-3.3.1.js') }}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.js"></script>
        @php
          $isNewSubscriber = isNewSubscriber();
        @endphp
        @if ( Request::is('/') || Request::is('explore') || $isNewSubscriber)
          <!-- Facebook Pixel Code -->
          <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '456256011606290');
            @if($isNewSubscriber) // track newly subscribed users
              fbq('track', 'Subscribe');
            @else { // don't track subscriber pages
              fbq('track', 'PageView');
            @endif
          </script>
          <noscript>
            <img height="1" width="1"
            src="https://www.facebook.com/tr?id=456256011606290&ev=PageView
            &noscript=1"/>
          </noscript>
          <!-- End Facebook Pixel Code -->
        @endif

    </head>
    <body class="d-flex flex-column h-100 {{ (Request::is('login') || Request::is('/')) ? 'bg' : '' }}">
      <header>
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              @php
                $user = Auth::user();
              @endphp
              <a href="{{ $user ? '/songs' : '/' }}"><img src="{{ asset('img/songwriterlogo-2.png') }}" class="image-logo" /></a>
            </div>
            @if(!Request::is('/') && !Request::is('explore'))
            @auth
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="socials">
                  <div class="text-center text-md-right">
                    <a role="button" href="/settings">
                      <i class="fas fa-cog"></i>
                    </a>
                    <a role="button" href="/feedbacks">
                        <i class="fas fa-envelope"></i>
                    </a>
                  </div>
              </div>
            </div>
            @endauth
            @endif

			@if(Request::is('explore'))
				<div class="col-lg-4 offset-lg-2 text-center">
					<a
						role="button"
						href="/select_payment"
						class="btn custom-primary white--text"
						style="width:100%; max-width:450px; padding: 30px 0;padding: 12px 0px;
						font-size: 25px;padding: 15px 0px;margin-top: 20px; font-weight: 500;border-radius: 8px;
						"
					> Subscribe Now! </a>
				</div>
			@endif
          </div>
        </div>
      </header>
      <main id="app">
          @yield('content')
      </main>
      <footer class="mt-auto py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-9">
              <img src="/img/footer-logo.png" style="max-width:60px;" alt="">
              <p class="d-inline-block ml-2">&copy; {{ now()->year }} All Things Michael Mclean</p>
              <div class="links">
                <a href="/terms-of-service">Terms of Service</a>
                <a href="/privacy-policy">Privacy Policy</a>
                <a href="/contact-us">Contact Us</a>
              </div>
            </div>
            <div class="col-md-3">
              <div class="socials text-center text-md-right">
                <a target="_blank" href="https://www.facebook.com/michaelmcleanmusic/">
                  <i class="fab fa-facebook-square"></i>
                </a>
                <a target="_blank" href="https://www.instagram.com/michaelmcleanmusic/">
                  <img style="position: relative; "src="/img/instagram-social.png" alt="">
                </a>
                <a target="_blank" href="https://www.youtube.com/channel/UCta5-IAIOzLSGjM4tiWRpbA">
                  <i class="fab fa-youtube"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </footer>
      <script src="{{ asset('js/app.js') }}"></script>
      @yield('script')
    </body>
</html>
