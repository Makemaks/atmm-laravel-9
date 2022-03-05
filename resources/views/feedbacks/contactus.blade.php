@extends('layouts.layout-full-screen')
@section('title', 'Contact Us')
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

        .contact_details {
            height: 200px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .contact_details li {
            margin-bottom:5px;
        }

        .contact_details li i {
            width:20px;
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
                Contact Us
            </span>
            <ul class="contact_details">
                <li><i class="fas fa-map-marker-alt"></i> P.O. Box 251, Heber City, Ut 84032</li>
                <!-- <li><i class="fas fa-envelope"></i> <a href="mailto:admin@songwritersundayschool.com">admin@songwritersundayschool.com</a></li> -->
                <li><i class="fas fa-phone"></i> <a href="(800) 976-39713">(800) 976-3971</a></li>
            </ul>
        </div>
        <div class="col-md-6 d-xl-block d-lg-block d-md-block d-none position-relative">
            <img src="/img/mclean.png" class="img-fluid banner-image">
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
                    <form style="padding-top: 20px;" action="/send-message" method="POST">
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
                            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <label class="label" for="formGroupExampleInput2"></label>
                                    <div>{!! app('captcha')->display() !!}</div>
                                </div>
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


{!! NoCaptcha::renderJs() !!}

@endsection
