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

        <link rel="stylesheet" href="{{ asset('css/checkout_style.css') }}" rel="stylesheet">
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
           @yield('script_src')

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
       @yield('content')
      @yield('script')
    </body>
</html>
