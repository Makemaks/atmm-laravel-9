@extends('layouts.layout-checkout')
@section('title', 'Select Payment')

@section('script_src')
 <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script> -->
<!-- <script src="http://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> -->
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
<div class="checkout-header-nav">
    <div class="container">
        <div class="row">
            <div class="col-md-12 cart-header-col">
                <ul class="cart-items-list-step">
                    <li>
                        <label>Monthly Total</label>
                        <label style="float:right">$4.23</label>
                    </li>
                </ul>
            </div> 
        </div>
    </div>
</div>
<div class="checkout-nav">
   <div class="wrapper "> 
        <div class="arrow-steps clearfix">
              <div class="step current"> <span> Personal</span> </div>
              <div class="step"> <span>Payment</span> </div>
              <div class="step"> <span> Account</span> </div>
              <div class="step"> <span>Review</span> </div>
        </div>
    </div>
    </div>
</div>
<div class="checkout-nav content-ck-nav">
     <div class="wrapper checkout-wrapper-form  step-content current-step"> 
        <label class="checkout-label">Personal Information</label>
        <label class="checkout-label-required "><big>*</big> Required Fields</label>
        <input name="fname_1"  type="text"  autocomplete="given-name" placeholder="*First Name" class="form-control checkout-form" >
        <input name="lname_1"  type="text"  placeholder="*Last Name" class="form-control checkout-form" >
        <input name="addrline1_1" type="text"  placeholder="*Address Line 1" class="form-control checkout-form" >
        <input name="addrline2_1" type="text"  placeholder="Address Line 2 (Optional)" class="form-control checkout-form" >
        <input name="city_1" type="text"  placeholder="*City" class="form-control checkout-form" >
        <div class="form-group row mb-0" >
            <div class="col-md-8 state_region" >
                <select  id="state" name="state_1" class="form-control checkout-form" >
                    <option value=""  disabled selected>*State/Region</option>
                </select>
            </div>
            <div class="col-md-4 zip_code" >
                <input name="zipcode_1" class="form-control checkout-form" >
            </div>
        </div>
        <input name="country_1" type="text" placeholder="Country" autocomplete="on" class="form-control checkout-form" >
        <input name="phone_1" type="text" placeholder="Phone Number" class="form-control checkout-form" >
    </div>
    <div class="wrapper checkout-wrapper-form  step-content "> 
        <label class="checkout-label">Payment Information</label>
        <label class="checkout-label-required "><big>*</big> Required Fields</label>
        <input name="name_on_card_1" type="text" placeholder="Name on Card" class="form-control checkout-form" >
        <select name="cardtype_1"  class="form-control checkout-form" >
            <option value="" disabled selected>*<i class="bi bi-credit-card"></i> Card Type</option>
            <option value="American Express">American Express</option>
            <option value="Discover">Discover</option>
            <option value="MasterCard">MasterCard</option>
            <option value="Visa">Visa</option>
        </select>
        <input name="cc_number_1" type="text" placeholder="Card Number" class="cardnumber form-control checkout-form" >
        <div class="form-group row" >
            <div class="col-md-6 xp_month" >
                <!-- <input name="xpiredatemonth_1" type="text" placeholder="Expiration Date Month" class="xpiredatemonth form-control checkout-form" > -->
                <?php $cur_month = (int) date('n') + 1; ?>
                <select name="expirationMonth_1"  class="form-control checkout-form">
                    <option value="" disabled selected>*Expiration Date Month</option>
                    @for($i=1;$i<=12;$i++)
                      <option value="<?php echo sprintf("%02d", $i); ?>"  >
                        <?php echo sprintf("%02d", $i); ?>
                      </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6 year" >
                 <!-- <input name="year_1" type="text"  placeholder="Year" class="year_input form-control checkout-form" > -->
                 <select id="expirationYear" name="expirationYear_1"  class="form-control checkout-form">
                    <option value="" disabled selected>*Year</option>
                   @php
                      $cur_yr = (int) date('Y');
                      $max_yr = $cur_yr + 10;
                    @endphp
                    @for( $i=$cur_yr; $i < $max_yr; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-top: 10px;">
          <input name="tos_1" type="checkbox"  >  <label id="tos_1"> I have read and agree to the </label>  <a href="#" data-toggle="modal" data-target="#terms"  >Terms of Agreement</a>
        </div>
    </div>
    <div class="wrapper checkout-wrapper-form  step-content "> 
        <label class="checkout-label">Account Setup</label>
        <label class="checkout-label-required "><big>*</big> Required Fields</label>
        <input name="email_1"  type="email" placeholder="Email Address" class="form-control checkout-form" >
        <input name="password_1"  type="password" pattern="(?=.*?[#?!@$%^&*-\]\[])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Password" class="form-control checkout-form" >
        <input name="password_confirmation_1"  type="password" pattern="(?=.*?[#?!@$%^&*-\]\[])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Confirm Password" class="form-control checkout-form" >
        <p>Password must be at least 8 characters and contain one capital letter, one number and one special character</p>
    </div>
    <form id="frmSubscription" class="wrapper checkout-wrapper-form-review   step-content "> 

        <div class="accordion" id="accordionExample">
            <div class="card checkout-review">
                <div class="card-header checkout-review-header" id="heading1">
                    <h5 class="mb-0">
                        <small><b>Personal Information </b></small>
                        <button class="btn dropdown-toggle float-right dd-toogle-btn " type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapseOne"></button>
                    </h5>
                </div>
                <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordionExample">
                    <div class="card-body">
                        <input name="fname" type="text" placeholder="*First Name" class="form-control checkout-form" >
                        <input name="lname" type="text" placeholder="*Last Name" class="form-control checkout-form" >
                        <input name="addrline1" type="text" placeholder="*Address Line 1" class="form-control checkout-form" >
                        <input name="addrline2" type="text" placeholder="*Address Line 2" class="form-control checkout-form" >
                        <input name="city" type="text" placeholder="*City" class="form-control checkout-form" >
                        <div class="form-group row mb-0" >
                            <div class="col-md-8 state_region" >
                                <select id="state2" name="state" class="form-control checkout-form" >
                                    <option value=""  disabled selected>*State/Region</option>
                                </select>
                            </div>
                            <div class="col-md-4 zip_code" >
                                <input name="zipcode" class="form-control checkout-form" >
                            </div>
                        </div>
                        <input name="country" type="text" placeholder="Country" class="form-control checkout-form" >
                        <input name="phone" type="text" placeholder="Phone Number" class="form-control checkout-form" >
                    </div>
                </div>
            </div>
            <div class="card checkout-review">
                <div class="card-header checkout-review-header" id="heading2">
                    <h5 class="mb-0">
                         <small><b>Payment Information</b></small>
                        <button class="btn dropdown-toggle float-right dd-toogle-btn " type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapseOne"></button>
                    </h5>
                </div>
                <div id="collapse2" class="collapse " aria-labelledby="heading2" data-parent="#accordionExample">
                    <div class="card-body">
                        <input name="name_on_card" type="text" placeholder="Name on Card" class="form-control checkout-form" >
                        <select name="cardtype"  class="form-control checkout-form" >
                            <option value="" disabled selected>*Card Type</option>
                            <option value="American Express">American Express</option>
                            <option value="Discover">Discover</option>
                            <option value="MasterCard">MasterCard</option>
                            <option value="Visa">Visa</option>
                        </select>
                        <input name="cc_number" type="text" placeholder="Card Number" class="cardnumber form-control checkout-form" >
                        <div class="form-group row" >
                            <div class="col-md-6 xp_month" >
                                <!-- <input name="xpiredatemonth" type="text" placeholder="Expiration Date Month" class="xpiredatemonth form-control checkout-form" > -->
                                <select name="expirationMonth"  class=" form-control checkout-form">
                                    <option value="" disabled selected>*Expiration Date Month</option>
                                    @for($i=1;$i<=12;$i++)
                                      <option value="<?php echo sprintf("%02d", $i); ?>"  >
                                        <?php echo sprintf("%02d", $i); ?>
                                      </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6 year" >
                                 <!-- <input name="year" type="text"  placeholder="Year" class="year_input form-control checkout-form" > -->
                                 <select id="expirationYear" name="expirationYear"  class="form-control checkout-form">
                                    <option value="" disabled selected>*Year</option>
                                   @php
                                      $cur_yr = (int) date('Y');
                                      $max_yr = $cur_yr + 10;
                                    @endphp
                                    @for( $i=$cur_yr; $i < $max_yr; $i++)
                                      <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 10px;">
                            <input name="tos" type="checkbox"  > <label id="tos"> I have read and agree to the </label><a  href="#" data-toggle="modal" data-target="#terms" class="terms" >Terms of Agreement</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card checkout-review">
                <div class="card-header checkout-review-header" id="heading3">
                    <h5 class="mb-0">
                        <small><b>Account Setup</b> </small>
                        <button class="btn dropdown-toggle float-right dd-toogle-btn " type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapseOne"></button>
                    </h5>
                </div>
                <div id="collapse3" class="collapse " aria-labelledby="heading3" data-parent="#accordionExample">
                    <div class="card-body">
                        <input name="email" type="email" placeholder="Email Address" class="form-control checkout-form" >
                        <input name="password" type="password" pattern="(?=.*?[#?!@$%^&*-\]\[])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Password" class="form-control checkout-form" >
                        <input name="password_confirmation" type="password" pattern="(?=.*?[#?!@$%^&*-\]\[])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Confirm Password" class="form-control checkout-form" >
                    </div>
                </div>
            </div>
            <div class="card checkout-review">
                <div class="card-header checkout-review-header" id="heading4">
                    <h5 class="mb-0">
                        <small><b>Cost Summary</b></small>
                        <button class="btn dropdown-toggle float-right dd-toogle-btn " type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="true" aria-controls="collapseOne"></button>
                    </h5>
                </div>
                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                    <div class="card-body">
                        <ul class="cart_items_list  pt-2 pb-2 black" >
                            <li>
                                <label >{{ $product_short_desc}}</label>
                                <label style="float:right">${{ $price }}</label>
                            </li>
                            <li>
                                <label>Tax</label>
                                <label style="float:right">${{ $tax }}</label>
                            </li>
                            <hr class="mt-0 mb-0" style="border-top: 2px solid rgb(175 175 175);">
                            <li>
                                <label>Total</label>
                                <label style="float:right">${{ $total }}</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="periodic" name="periodic" value="{{ $periodic }}">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="company" name="company" value="N/A">
        <input type="hidden" id="paymentType" name="paymentType" value="creditcard">
        <input id="shippingRequired" name="shippingRequired" type="hidden" value="false" />
        <input class="inf_507f0861f3945b48d89d99e40521f614" id="inf_1Sj54Kt5aG5lNlsq" name="inf_1Sj54Kt5aG5lNlsq" type="hidden" />
        <br>
        <div class="text-center">{!! app('captcha')->display() !!}</div>
    </form>
    <div id="submitresult"></div>
</div>
<div class="container footed_fixed">
    <div class="row">
        <div class="col-md-12 text-center">
          
            <a role="button" href="#" class=" btn custom-primary white--text hide_back prev wizard-btn" >  Previous </a>
            
            <a role="button" href="#" class=" btn custom-primary white--text next wizard-btn" > Continue </a>
            <!-- <button type="button"  onclick="location.href = 'm-checkout-last';" id="submit_btn" class=" btn custom-primary white--text next confirm-btn hide_back" > Confirm </button> -->
             <button type="button" id="submit_btn" class=" btn custom-primary white--text confirm-btn hide_back" > Confirm </button>
        </div> 
    </div>
</div>



<!-- The Modal -->
<div class="modal fade terms" id="terms">
<div class="modal-dialog modal-lg modal-dialog-centered">
  <div class="modal-content">

    <!-- Modal Header -->
    <div class="modal-header">
      <h4 class="modal-title" style="color:black;"><strong>Agreement between User</strong> and <strong><a href="/">SongwriterSundaySchoool.com</a></strong></h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <!-- Modal body -->
    <div class="modal-body" style="color:black;">
      @include('terms-of-service-popup')
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>

  </div>
</div>
</div>

 {!! NoCaptcha::renderJs() !!}
@endsection
@section('script')

<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="{{ asset('js/dynamic_location.js') }}"></script>
<script src="{{ asset('js/dynamic_location_geodata.js') }}"></script>
<script src="{{ asset('js/checkout.js') }}"></script>
<script src="{{ asset('js/mask.js') }}"></script>

@endsection