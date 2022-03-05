@extends('layouts.layout-full-screen')
@section('title', 'Select Payment')

@section('style')
<style type="text/css">

    .subscription-container {
        margin-top:38px;
    }

    .subscription {
        position: relative;
        background: #F4F4F4;
        height:210px;
        text-align: center;
        padding-top:50px;
    }

    .subscription .promo {
        background: #D7D7D7;
        position: absolute;
        top: 0;
        height: 14%;
        width: 100%;
        text-align: center;
        left: 0;
        font-size: 19px;
        font-weight: bold;
        color: #fff;
        padding-top: 3px;
    }

    .subscription .type {
        display: block;
        font-size: 23px;
        font-weight: 800;
    }

    .subscription .price {
        font-size: 47px;
        font-weight: 300;
        position: relative;
        top: -20px;
        margin-bottom: 0;
    }

    .subscription .price .currency {
        font-size: 38px;
        padding-right: 3px;
    }

    .subscription .info {
        text-align: center;
        color:#9b9a9a;
        margin: 0;
        position: relative;
        top: -30px;
        font-weight: bold;
        font-size: 13px;
    }

    .subscription .btn {
        position: absolute;
        width: 85%;
        left: 7.5%;
        bottom: 5%;
    }

    @media (max-width: 1199.98px) {

        h2 {
            font-size: 1.8rem;
        }

        .subscription-container {
            margin-top:0;
        }

        .subscription {
            height: 170px;
            padding-top: 42px;
        }

        .subscription .promo {
            height: 18%;
            font-size: 19px;
            padding-top: 3px;
        }

        .subscription .type {
            font-size: 17px;
        }

        .subscription .price {
            font-size: 35px;
            top: -14px;
        }

        .subscription .price .currency {
            font-size: 29px;
            padding-right: 3px;
        }

        .subscription .info {
            top: -20px;
            font-size: 13px;
        }

    }

    @media (max-width: 991.98px) {
        h2 {
            font-size: 1.4rem;
        }
        .unordered-list li {
            font-size:14px;
            line-height: 13px
        }

        .subscription {
            height: 130px;
            padding-top: 26px;
        }

        .subscription .promo {
            height: 18%;
            font-size: 13px;
            padding-top: 3px;
        }

        .subscription .type {
            font-size: 15px;
        }

        .subscription .price {
            font-size: 26px;
            top: -10px;
        }

        .subscription .price .currency {
            font-size: 20px;
            padding-right: 3px;
        }

        .subscription .info {
            top: -14px;
            font-size: 10px;
        }
    }

    /* Small devices (landscape phones, less than 768px) */
    @media (max-width: 767.98px) {
        h2 {
            margin-bottom:20px;
        }

        .unordered-list {
            max-width:350px;
            margin:20px auto;
        }

        .unordered-list li {
            font-size:16px;
            line-height: initial;
        }

        .subscription {
            height:240px;
            padding-top:50px;
            max-width:350px;
            margin:0 auto;
        }

        .subscription .promo {
            height: 14%;
            font-size: 19px;
        }

        .subscription .type {
            font-size: 23px;
        }

        .subscription .price {
            font-size: 47px;
            top: -20px;
        }

        .subscription .price .currency {
            font-size: 38px;
        }

        .subscription .info {
            top: -30px;
            font-size: 13px;
        }
    }

    /* Extra small devices (portrait phones, less than 576px) */
    @media (max-width: 575.98px) {

    }

    @media (max-width: 320px) {
        .unordered-list li {
            font-size:14px;
            line-height: initial;
        }
    }
</style>
@endsection
@section('content')
<div class="container">
    @php
    /*
    <div class="row">
        <div class="col-sm-12 songwriter_sunday_school">
            <div class="row" align="center">
                <!-- <img src="{{ asset('img/songwriterlogo.png') }}" class="image-logo" /> -->
                <img src="{{ asset('img/songwriterlogo.png') }}" class="image-logo-payment img-fluid" />
                <!-- <img src="{{ asset('img/Signup_Page.jpg') }}" class="img-fluid" /> -->
            </div>
        </div>
    </div>
    */
    @endphp
    <div class="row">
        <div class="offset-md-6 col-md-6 col-xl-5 col-sm-12">
            <!-- <h2 class="text-center">Become A Member</h2> -->
            <h2 class="text-center">Subscribe</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 order-xl-2 order-lg-2 order-md-2 order-sm-2 order-2">
            <ul style="padding-left:15px;">
                <li>Stream Michael McLeans Complete Music Collection!</li>
                <li>Exclusive, Uplifting Video Messages From Michael</li>
                <li>Sing Along with Karaoke & Instrumental Videos</li>
                <li>Download &amp; Print Sheet Music</li>
                <li>
                    Podcasts &amp; Audiobooks
                </li>
            </ul>
            <div class="row subscription-container">
                <div class="col-xl-10 col-lg-11 col-md-12">
                    <div class="row">

                          @foreach($products as $ind => $product)
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 25px;">
                                <div class="subscription">
                                    <span class="type"> {{ ucfirst($product->product_name) }} </span>
                                    <p class="price">
                                        <span class="currency">$</span>{{ $product->product_price }}<span class="currency">/MO</span>
                                    </p>
                                    <!-- <p class="info">(+ tax)</p> -->
                                    <!-- <a href="{{ config('app.url')}}/subscription/monthly" role="button" class="btn btn-sm d-block mx-auto custom-primary" >SIGN UP </a> -->
                                    <a href="{{ config('app.url')}}/m-checkout/monthly" role="button" class="btn btn-sm d-block mx-auto custom-primary" >SIGN UP </a>
                                      
                                </div>
                            </div>
                          @endforeach

                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 order-xl-1 order-lg-1 order-md-1 order-sm-1 order-1">
            <!-- <img src="{{ asset('img/mc-lean-web-2-edit-crop.png') }}" width="100%" class="image-mc-lean-side-right" /> -->
            <img src="{{ asset('img/select_payment.png') }}" class="img-fluid" />
        </div>
    </div>
    <!--
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="footer"> FOOTER </div>
        </div>
    </div>
    -->
</div>
@endsection
