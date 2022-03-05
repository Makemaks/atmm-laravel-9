@extends('layouts.layout-checkout')
@section('title', 'Select Payment')

@section('script_src')
 <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script> -->
<script src="http://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>
@endsection

@section('content')
<div class="container header-step">
    <div class="row">
        <div class="col-sm-12 text-center">
          @php
            $user = Auth::user();
          @endphp
          <a href="{{ $user ? '/songs' : '/' }}"><img src="{{ asset('img/checkout_logo2_step.svg') }}" class="checkout-logo-step" /></a>
        </div>
    </div>
</div>
<div class="container header-step">
    <div class="row">
        <div class="col-sm-12 text-center p-0">
            <a href="{{ $user ? '/songs' : '/' }}"><img src="{{  asset('img/select_payment.png') }}" class="checkout-logo-step-2" /></a>
        </div>
        <div class="col-sm-12 text-center ">
            <h2 class="app_download text-center">Download the app!</h2></a>
        </div>
        <div class="col-md-12 text-center">
            <a href="https://apps.apple.com/us/app/all-things-michael-mclean/id1591831656"><img src="{{  asset('img/appstore.png') }}"  class="app_btn" /></a>
            <a href="https://play.google.com/store/apps/details?id=com.allthingsmichaelmclean.app" ><img src="{{  asset('img/appstore-gplay.png') }}"   class="app_btn"/></a>
        </div>
        <div class="col-sm-12 text-center mt-4 ">
             <a href="{{ $user ? '/songs' : '/' }}" class="continue_web"> Or click here to continue to the website.</a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <a role="button" href="#" > <img>  </a>
            <a role="button" href="#" > <img></a>
        </div>
    </div>
</div>
@endsection
