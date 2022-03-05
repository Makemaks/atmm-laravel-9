@extends('layouts.layout-checkout')
@section('title', 'Select Payment')

@section('content')
 <div class="container header">
    <div class="row">
        <div class="col-sm-12 text-center">
          @php
            $user = Auth::user();
          @endphp
          <a href="{{ $user ? '/songs' : '/' }}"><img src="{{ asset('img/checkout_logo2.svg') }}" class="checkout-logo" /></a>
        </div>
    </div>
</div>
 <div class="cart_items">
    <div class="container line_items">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h5 class="text-center cart_header" >All things Michael McLean in your pocket!</h5>
                <ul class="cart_items_list  pt-2 pb-2">
                    <li>
                        <label>{{ $product_short_desc}}</label>
                        <label style="float:right">${{ $price }}</label>
                    </li>
                    <li>
                        <label>Tax</label>
                        <label style="float:right">${{ $tax }}</label>
                    </li>
                    <hr>
                    <li>
                        <label>Total</label>
                        <label style="float:right">${{ $total }}</label>
                    </li>
                </ul>
            </div> 
        </div>
    </div>
</div> 
<div class="container footed_fixed_first">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-center">
            <a role="button" href="/m-checkout-next/monthly" class="btn custom-primary white--text cart_checkout_btn" > Begin Checkout </a>
        </div> 
    </div>
</div>
@endsection
