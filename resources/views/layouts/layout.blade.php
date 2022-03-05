<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>All Things Michael Mclean</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <!-- Favicon -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href="{{ asset('css/custom-css-2.css') }}" rel="stylesheet">
        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
        <style>
            .back-image {
                background-image: url("{{ asset('img/home_bg.png') }}");
                width: 100%;
                height: 579px;
            }
            .songwriter_sunday_school {
                font-weight: bold;
                font-size: 24px;
                color: #4a4a4a;
                padding: 40px 103px;
            }
            .hero_headline {
                font-size: 45px;
                color: #4a4a4a;
            }
            .hero_headline_content {
                max-width: 100%;
                font-size: 21px;
                color: #565151;
                line-height: 40px;
                padding-top: 17px;
            }
            .explore {
                background-color: #009688;
                border-color: #008679;
                width: 32%;
                color: #fff;
            }
            .explore:hover {
                background-color: #008679;
            }
            .explore:active {
                background-color: #008679;
            }
            .unlimited_access {
                font-size: 24px;
                font-weight: bold;
                color: #4a4a4a;
            }
            .unlimited_access_content {
                font-size: 22px;
                color: #656060;
                margin-left: -23%;
                line-height: 45px;
                padding-top: 15px;
            }
            .card_style {
                background-color: #f5f5f5;
                border-color: #f5f5f5;
                width: 90%;
            }
            .inputs {
                background-color: #d5d5d5;
                border-radius: 0;
            }
            .inputs:focus {
                color: #495057;
                background-color: #d5d5d5;
                border-color: #80bdff;
                outline: 0;
                /* box-shadow: 0 0 0 0.2rem rgba(0,123,25) */
            }
            .footer {
                position: relative;
                right: 0;
                bottom: 0;
                left: 0;
                padding: 2rem;
                background-color: #efefef;
                text-align: center;
                color: #fff;
                font-size: 40px;
            }
            html {
                height: 100%;
                box-sizing: border-box;
            }

            *,
            *:before,
            *:after {
                box-sizing: inherit;
            }

            body {
                position: relative;
                margin: 0;
                min-height: 100%;
                font-family: "Helvetica Neue", Arial, sans-serif;
            }
            .button_nav {
                border-radius: 22px;
                color: #4a4a4a;
                font-weight: bold;
                width: 13%;
                padding: 6px;
                background-color: #d6d6d6;
            }
            .button_nav:hover {
                background-color: #9b9b9b;
            }

            .button_nav:focus {
                background-color: #9b9b9b;
            }
            @media only screen and (max-width: 990px) {
                .explore {
                    margin-left: 100px;
                    width: 50%;
                }
            }
            .btn_round {
                border-radius: 999px;
            }

            .modal-xl {
                max-width: 1140px;
            }
        </style>
        @yield('style')
    </head>
    <body style="font-family: 'Raleway', sans-serif;">
        <div id="app">
            <main>
                @yield('content')
            </main>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
