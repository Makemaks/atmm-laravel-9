<?php /************* From Infusionsoft form **************/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="{{ asset('css/custom-css-2.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">

    <link href="{{ asset('infusionsoftform/resources/styledcart/css/styledcart.css') }}" media="all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('infusionsoftform/resources/styledcart/css/appearance.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('infusionsoftform/resources/styledcart/css/layout.css') }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript"> var base_url = '{{ config('app.url')}}'; </script>


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
    fbq('track', 'PageView');
    fbq('track', 'InitiateCheckout');
    </script>
    <noscript>
     <img height="1" width="1"
    src="https://www.facebook.com/tr?id=456256011606290&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

</head>

<body>
    <div id="wrapper">

        <form id="frmSubscription" method="post">
            <div id="header">
                <div id="IMAGE">
                    <div id="companyLogoTopBanner">
                        <img src="{{ asset('infusionsoftform/resources/styledcart/images/logov2.jpeg') }}" />
                    </div>
                </div>
            </div>


            <div id="submitresult"></div>
            <div id="content">
                <div id="ORDER_FORM_PRODUCT_LIST">
                    <table class="viewCart">

                        <tr>
                            <th class="leftAlign">Products</th>
                            <th></th>
                            <th class="rightAlignPrice priceCell">Price</th>
                            <th class="centerAlign qtyCell">Quantity</th>
                            <th class="rightAlignPrice priceCell">Total</th>
                        </tr>
                        <tr>
                            @if ($periodic == 'monthly')
                                <td colspan="2" class="productCell">
                                    <h1><b> {{ $productname }}  </b></h1>
                                    @php
                                    /*
                                    <p class="productDescription">{{ $product_short_desc }}</p>
                                    <span class="subscriptionPlan">${{$price}} / month</span>
                                    */
                                    @endphp

                                    <p class="productDescription">Monthly</p>
                                    <span class="subscriptionPlan">$7.41 / month</span>
                                </td>

                                <td class="rightAlignPrice priceCell"><span class="price">${{$price}}</span></td>
                                <td class="centerAlign qtyCell">1</td>
                                <td class="rightAlignPrice priceCell price">${{$price}}</td>
                            @endif

                            @php /*
                            @if ($periodic == 'yearly')
                                <td colspan="2" class="productCell">
                                    <h1><b> {{ $productname }}  </b></h1>
                                    <p class="productDescription">{{ $product_short_desc }}</p>
                                    <span class="subscriptionPlan"><span class="sub_total">${{$price}}</span> / year</span>
                                </td>
                                <td class="rightAlignPrice priceCell">
                                    <span id="change_label_price" class="price sub_total">${{$price}}</span>
                                </td>
                                <td class="centerAlign qtyCell">1</td>
                                <td class="rightAlignPrice priceCell pric sub_total">${{$price}}</td>
                            @endif
                            */ @endphp
                        </tr>
                        <tr class="subtotal">
                            <td class="leftAlign"><span class="totalPrice">Subtotal</span> <br>**Price Includes Tax</td>
                            <td co colspan="2"></td>
                            <td class="qtyCell"></td>
                            <td class="rightAlignPrice priceCell price"><span class="priceBold sub_total">${{$price}}</span></td>
                        </tr>
                    </table>
                </div>
                <div id="ORDER_FORM_BILLING_ENTRY">
                    <link href="{{ asset('infusionsoftform/resources/styledcart/css/anti_spam.css') }}" media="all" rel="stylesheet" type="text/css" />
                    <div id="orderFormBillingEntry">
                        <table class="billingTable">
                            <tr>
                                <th colspan="2" class="leftAlign">Personal Information</th>
                            </tr>
                            <tr>
                                <td class="rightAlignTop"><label class="checkoutLabel">* First Name</label></td>
                                <td>
                                    <input class="inline-invalid-styling checkoutTop" id="fname" name="fname" size="10" type="text" required="required">
                                    <input class="inf_507f0861f3945b48d89d99e40521f614" id="inf_1Sj54Kt5aG5lNlsq" name="inf_1Sj54Kt5aG5lNlsq" type="text" />
                                </td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">* Last Name</label></td>
                                <td><input class="inline-invalid-styling checkout" id="lname" name="lname" size="12" type="text" required="required"></td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">Company Name</label></td>
                                <td><input class="checkout" id="company" name="company" size="25" type="text"></td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">* Address - Line 1</label></td>
                                <td>
                                    <input class="inline-invalid-styling checkout" id="addrline1" name="addrline1" size="25" type="text" required="required">
                                </td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">Address - Line 2</label></td>
                                <td><input class="checkout" id="addrline2" name="addrline2" size="25" type="text"></td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">* City</label></td>
                                <td><input class="inline-invalid-styling checkout" id="city" name="city" size="15" type="text" required="required" ></td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel"><div id="stateRequired">* State / Region</div></label></td>
                                <td>
                                    <!--
                                    <input class="inline-invalid-styling checkoutTop" id="state" name="state" size="2" type="text"> -->
                                    <select class="inline-invalid-styling checkoutTop" name="state" id="state"></select>
                                </td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">* Zip Code</label></td>
                                <td><input type="text" class="inline-invalid-styling checkoutShortest" id="zipcode" name="zipcode" size="5" type="text" required="required"></td>
                            </tr>
                            <tr>
                                <td class="rightAlign"><label class="checkoutLabel">* Country</label></td>
                                <td>
                                  <select class="checkout" required="required" class="inf-select is-component" id="country" name="country">
                                  {{--
                                  @if(count($countries) > 0)
                                      @foreach ($countries as $ind => $val)
                                          <option value="{{ $ind }}" {{ ($ind=='USA' ? 'selected':'') }} >
                                            {{ $val }}
                                          </option>
                                      @endforeach
                                  @endif
                                  --}}
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="rightAlign">* Phone Number</td>
                                <td><input type="text" class="inline-invalid-styling checkout" id="phone" name="phone" size="25" type="text" required="required"></td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="ORDER_FORM_SHIPPING_ENTRY">
                    <div id="orderFormShippingEntry">
                    </div>
                </div>
                <div id="SHIPPING_OPTIONS">
                    <div id="shippingOptionsContainer">
                    </div>
                </div>
                <div id="PAYMENT_PLANS">
                </div>
                <!-- <div id="ORDER_FORM_SUMMARY"> -->
                <div id="orderFormBillingEntry">

                    <table class="orderSummary">
                        <tr>
                            <th class="leftAlign">Account Setup</th>
                            <th class="rightAlignPrice"></th>
                        </tr>
                        <tr>
                            <td class="rightAlign1 orderSummary_account_setup">* Email Address</td>
                            <td class="orderSummary_account_setup"><input type="email" class="inline-invalid-styling checkout" id="email" name="email" required></td>
                        </tr>
                        <tr>
                            <td class="rightAlign1 orderSummary_account_setup">* Password</td>
                            <td class="orderSummary_account_setup">
                              <input type="password" class="inline-invalid-styling checkout" id="password" name="password" required>
                              <div style="font-size:12px;width: 200px;">Must be 8 characters and contain 1 capital, 1 number and 1 special character ($,#,! or *)</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="rightAlign1 orderSummary_account_setup">*Confirm Password</td>
                            <td class="orderSummary_account_setup">
                             <input type="password" class="inline-invalid-styling checkout" id="password_confirmation" name="password_confirmation" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="rightAlign1 orderSummary_account_setup">&nbsp;</td>
                            <td class="orderSummary_account_setup">&nbsp;</td>
                        </tr>
                    </table>

                    <table class="orderSummary">
                        <tr>
                            <th class="leftAlign">Order Summary</th>
                            <th class="rightAlignPrice"></th>
                        </tr>
                        <tr>
                            <td class="listCell">Subtotal <br>**Price Includes Tax</td>
                            <td class="rightAlignPrice sub_total">${{$price}}</td>
                        </tr>
                        @php /*
                        <tr>
                            <td class="orderSummary_account_setup">Tax</td>
                            <td class="orderSummary_account_setup" id="tax_applied">${{$tax}}</td>
                        </tr>
                        */ @endphp
                        <tr>
                            <td class="subtotal">Total Due <br>**Price Includes Tax</td>
                            <td class="rightAlignPrice subtotal total_due">
                                ${{$total}}
                            </td>
                        </tr>

                        @if ($periodic == 'yearly')
                        <tr>
                            <td class="orderSummary_account_setup1">Apply Discount</td>
                            <td class="orderSummary_account_setup1">
                                <input class="inline-invalid-styling checkout" id="apply_discount" name="apply_discount" type="text">
                                <span id="discount_indicator"></span>
                                <!-- <span class="continueButton">Validate Code</span> -->
                            </td>
                        </tr>


                         @endif

                    </table>
                </div>
                <div id="PAYMENT_SELECTION">
                    <table id="paymentSelection" class="paymentMethodTable">
                        <input id="shippingRequired" name="shippingRequired" type="hidden" value="false" />
                        <tr>
                            <th colspan="5" class="leftAlign">Payment Information</th>
                        </tr>
                        <input id="creditCardType" name="paymentType" type="hidden" value="creditcard" />
                        <tr>
                            <td>
                                <img src="{{ asset('infusionsoftform//resources/styledcart/images/paymenttypes/creditcard.png') }}" class="paymentIcon" /><span class="smallHeader">Credit card</span>
                            </td>
                        </tr>
                        <!-- creditCardForm v2 -->
                        <input type="hidden" id="languageCode" value="en" />
                        <tr class="cellLow">
                            <td class="pay1">
                                <span class="paymentLabel">Credit Card Type</span>
                                <select class="checkout inf-select is-component" id="cardType" name="cardType" size="1">
                                    <option value="American Express">American Express</option>
                                    <option value="Discover">Discover</option>
                                    <option value="MasterCard">MasterCard</option>
                                    <option value="Visa">Visa</option>
                                </select>
                            </td>
                            <td class="pay2">
                                <span class="paymentLabel">Credit Card Number</span>
                                <input type="text" class="regula-validation checkout" id="cc_number" name="cc_number" maxlength="16">

                            </td>
                            <td class="pay3">
                                <span class="paymentLabel">Expiration Date</span>
                                <?php $cur_month = (int) date('n') + 1; ?>
                                <select class="checkoutShortest" id="expirationMonth" name="expirationMonth" size="1">
                                    @for($i=1;$i<=12;$i++)
                                      <option value="<?php echo sprintf("%02d", $i); ?>" {{ ($i==$cur_month ? 'selected':'') }} >
                                        <?php echo sprintf("%02d", $i); ?>
                                      </option>
                                    @endfor
                                </select>
                                <select class="checkoutShortest" id="expirationYear" name="expirationYear" size="1">
                                    @php
                                      $cur_yr = (int) date('Y');
                                      $max_yr = $cur_yr + 10;
                                    @endphp
                                    @for( $i=$cur_yr; $i < $max_yr; $i++)
                                      <option>{{ $i }}</option>
                                    @endfor
                                </select>
                            </td>
                            <td class="pay4">
                                <span class="paymentLabel">Name on Card</span>
                                <input type="text" class="regula-validation checkout" id="name_on_card" name="name_on_card">
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3">

                              <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="tos" id="tos">
                                <label class="form-check-label">
                                    I have read and agree to the <a tabindex="-1" href="#" data-toggle="modal" data-target="#terms">Terms Agreement</a>
                                </label>
                              </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                    <div>{!! app('captcha')->display() !!}</div>
                                </div>
                            </td>
                        </tr>
                    </table>



                </div>
                <div id="CHECKOUT_LINKS">
                    <div class="checkoutLinks">
                        <input type="hidden" id="periodic" name="periodic" value="{{ $periodic }}">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <span id="btnPlaceOrder" onclick="subscribe()" class="continueButton">Place Order</span>

                        <!--
                        <input id="btnPlaceOrder" onclick="subscribe()" type="button" value="Place Order" class="btn btn-success btn-lg" > -->
                        <span id="loading_img"></span>

                    </div>
                </div>
            </div>
        </form>
    </div>


      <!-- The Modal -->
      <div class="modal fade terms" id="terms">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title"><strong>Agreement between User</strong> and <strong><a href="/">AllThingsMichaelMclean.com</a></strong></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              @include('terms-of-service-popup')
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

          </div>
        </div>
      </div>
      @include('thank-you')

{!! NoCaptcha::renderJs() !!}

<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="{{ asset('js/dynamic_location.js') }}"></script>
<script src="{{ asset('js/dynamic_location_geodata.js') }}"></script>

</body>
</html>
