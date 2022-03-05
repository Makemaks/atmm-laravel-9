@extends('layouts.layout-full-screen')
@section('title', 'Feedback')
@section('style')
    <link rel="stylesheet" type="text/css" href="/css/media-page.css">
    <style>
        .content-padding .video-category {
            margin-bottom:50px;
        }

        .content-padding .video-category:last-child {
            margin-bottom:0;
        }

        .content-padding .video-category h3 {
            text-align: center;
            font-weight: bold;
            font-size: 21px;
            margin-bottom:25px;
        }

        .hero_headline_content {
            height: 200px;
        }

        .video-category {

        }

        .video-category h3 {
            font-size: 24px;
        }

        .video-category .video-container {
            position: relative;
        }

        .video-category .video-container p.title {
            font-weight: bold;
            font-size:13px;
            margin-top:5px;
        }

        .video-category .video-container span.duration {
            position: absolute;
            right: 5px;
            bottom: 1px;
            font-size: 13px;
            font-weight: bold;
        }

        .link-container {
            padding-right: 0;
        }

        @media (max-width: 1199.98px) {
            .hero_headline_content {
                height: 185px;
            }
        }

        @media (max-width: 991.98px) {

            .has-search .form-control {
                height: 33px;
                font-size: 13px;
            }

            .has-search .form-control-feedback {
                top: -2px;
                font-size: 13px;
            }

            .hero_headline_content {
                height: 90px;
            }
        }

        @media only screen and (max-width : 768px) {
            .has-search .form-control {
                margin: 0 auto;
                width: 95%;
                height: 38px;
            }

            .hero_headline_content {
                height: auto;
            }
        }

        @media only screen and (max-width : 575px) {

        }
    </style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <span class="hero_headline">

            </span>
            <span class="hero_headline_content">
            </span>
        </div>
        <div class="col-md-6 d-xl-block d-lg-block d-md-block d-none position-relative">
            <img src="/img/mclean.png" class="img-fluid banner-image">
        </div>
    </div>
    @include('media-links')
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-lg-12">
            <div class="card" style="background-color: #f1f1f1;border-radius:0">
                <div class="card-body">
                    <div class="row">
                        <div
                            class="col-lg-6 col-md-6 col-sm-6 col-sm-12"
                            style="text-align: left;"
                        >
                            Name :
                            @if (Auth::user())
                                {{ Auth::user()->name }}
                            @endif
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
                            style="text-align: right;"
                        >
                            <a href="{{ route('logout') }}"
                                class="btn custom-primary"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt fa-fw"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-bottom: 65px;">
        <div class="col-lg-12">
            <div class="card" style="background-color: #f1f1f1;border-radius:0">
                <div class="card-body">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <p class="card-title card-title-text">We'd love to hear from you.</p>
                    </div>
                    @include('layouts.flash_messages')
                    <form style="padding-top: 20px;" action="/send-feedback" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label class="label" for="formGroupExampleInput">Your Name (required)</label>
                                <input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label class="label" for="formGroupExampleInput2">Your Email (required)</label>
                                <input type="email" name="email" class="form-control" id="formGroupExampleInput2" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label class="label" for="formGroupExampleInput2">Subject (required)</label>
                                <input type="text" name="subject" class="form-control" id="formGroupExampleInput2" placeholder="Subject">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label class="label" for="formGroupExampleInput2">Your Message (required)</label>
                                <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-success">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection