@extends('layouts.layout-full-screen')
@section('title', 'Home')
@section('style')
    <style>
        h2 {
            margin-bottom:32px;
            font-weight: bold;
            font-size:27px;
            line-height: 40px;
            /* font-size:35px;
            line-height: 48px; */
        }

        .btn.custom-primary {
            min-width:180px;
            font-weight: bold;
        }

        .faded-white-container
        {
            position:relative;
            background-color: rgba(255, 255, 255, 0.9);
            -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
                    box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        }
        .faded-white-container {
            /* position:relative;
            -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.1), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.1), 0 0 40px rgba(0, 0, 0, 0.1) inset;
                    box-shadow:0 1px 4px rgba(0, 0, 0, 0.1), 0 0 40px rgba(0, 0, 0, 0.1) inset; */
        }

        .faded-white-container .btn {
            width:250px;
            margin-bottom:30px;
        }


        .faded-white-container h3 {
            text-align: center;
            font-weight: bold;
            margin-bottom:25px;
        }

        input {
            font-size: 20px;
            font-weight: 700 !important;
        }

        input::placeholder {
            font-size:20px;
            color:#b2b2b2 !important;
        }

        /* #login-button {
            margin-top:65px;
        }

        @media (min-width: 1397px) {
            #login-button {
                margin-top:40px;
            }
        } */

        .temporary_close_wrapper {
            background-color:#212529;
            color:#5ba6f0;
            padding: 20px 0px 20px 0px;
        }
        .temporary_close_txt {
            font-size: 20px;
            font-weight: bold;
        }
        .temporary_close_below_box {
            min-height: 270px !important;
        }
        .subscribe_btn {
            margin-top: 36px !important;
        }

        @media (min-width: 1200px) {
            h2 {
              font-size:35px;
              line-height: 45px;
            }

            p {
                font-size:18px;
            }
        }

        @media (max-width: 1199.98px) {

            main {
                padding:10px 0;
            }

            header {
                padding: 20px 0;
            }

            h2 {
                margin-bottom: 20px !important;
                line-height: 32px;
            }

        }

        @media (max-width: 991.98px) {
            h2 {
                font-size:21px;
                margin-bottom: 14px !important;
            }

            p {
                font-size: 12px;
            }

            .faded-white-container {
                font-size: 12px;
            }

            .faded-white-container .btn {
                margin-bottom:20px;
            }

            .faded-white-container ul  {
                padding-left: 32px;
            }
        }

        /* Small devices (landscape phones, less than 768px) */
        @media (max-width: 767.98px) {

            .image-logo {
                display: block;
                margin: 0 auto;
            }

            h2 {
              text-align: center;
            }

            p {
                text-align: center;
            }

            .faded-white-container:first-child {
                margin-bottom:20px !important;
                font-size: 16px;
            }
        }

        /* Extra small devices (portrait phones, less than 576px) */
        @media (max-width: 575.98px) {
            h2 {
                font-size: 20px;
                line-height: 30px;
            }
        }

        @media (max-width: 375px) {
            h2 {
                font-size: 18px;
                line-height: 30px;
            }
        }

        @media (max-width: 320px) {
            h2 {
                font-size: 15px;
                line-height: 27px;
            }
        }

        @media (max-width: 1280px) and (max-height: 720px) {
            h2 {
                margin-bottom: 25px !important;
            }
        }


        @media (max-width: 1024px) and (max-height: 768px) {
            h2 {
                margin-bottom: 20px !important;
            }
        }

        @media (max-width: 800px) and (max-height: 768px) {
            h2 {
                margin-bottom: 14px !important;
            }
        }
    </style>
@endsection

@section('content')
<div class="main-padding">
    <div class="container">
        <div class="row mb-md-5 mb-4">
            <div class="col-lg-8 col-md-7">
                <h2>"Sometimes a song can teach us a truth<br>the only way our hearts can hear it..."</h2>
                @php /*
                <p>
                    Click the <a href="#"
                    data-toggle="modal"
                    data-target="#modal-form" class="/custom-link">Explore</a>
                    button and get an exclusive look<br> at all amazing
                    content. Check it out!
                </p>

                <a class="btn custom-primary d-md-inline-block d-block mx-auto" href="#"
                data-toggle="modal"
                data-target="#modal-form">Explore</a>
                */ @endphp
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
<!--
                <div class="temporary_close_wrapper" align="center">
                    <span class="temporary_close_txt">TEMPORARILY CLOSE FOR CONSTRUCTION</span>
                </div> -->
                <div class="faded-white-container become-a-member-container temporary_close_below_box">
                    <div class="row">
                      <div class="col-md-6">
                      <label>
                          Install the Google Authenticator in your mobile device and scan this QR code to generate your One Time Password(OTP).
                          Google Authenticator is available in Google Play and App Store.
                      </label>
                      </div>
                      <div class="col-md-6">
                      @php
                        Google2FA::setQRCodeBackend('svg');
                        $qr_code_source = Google2FA::getQRCodeInline(
                            'All Things Michael Mclean',
                            'admin@songwriter.com',
                            'JA5F4SKHZIFA25GW'
                        );
                        echo $qr_code_source;
                      @endphp
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">



                <div class="faded-white-container become-a-member-container temporary_close_below_box" >

                    @include('layouts.flash_messages')
                    <form method="POST" action="/do_login">
                        @csrf
                            <div class="form-group row">
                                <div class="col-sm-12" style="padding-left: 30px; padding-right: 30px;">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email"
                                        style="border-radius: 30px;"
                                        value="{{ old('email') }}"
                                        placeholder="EMAIL"
                                        required
                                        autofocus
                                    />
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12" style="padding-left: 30px; padding-right: 30px;">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="password"
                                        style="border-radius: 20px;"
                                        placeholder="PASSWORD"
                                        required
                                    />
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12" style="padding-left: 30px; padding-right: 30px;">
                                    <input
                                        id="one_time_password"
                                        type="text"
                                        class="form-control{{ $errors->has('one_time_password') ? ' is-invalid' : '' }}"
                                        name="one_time_password"
                                        style="border-radius: 20px;"
                                        placeholder="One Time Password"
                                        required
                                    />
                                    @if ($errors->has('one_time_password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('one_time_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if (Route::has('password.request'))
                                    <a class="btn btn-link mb-1" style="color: black;font-size: 16px;
                                    width: 100%;
                                    position: relative;" href="{{ route('password.request') }}">
                                        {{ __('Forgot password') }}
                                    </a>
                                @endif
                            <button type="submit" id="login-button" class="btn custom-primary d-block mx-auto mb-0 mt-3" >{{ __('Login') }}</button>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">
                <small>
                    In order to explore content, please provide the following:
                </small>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <!--
            <form
                class="form-horizontal"
                action="https://nv681.infusionsoft.com/app/form/process/bc186b4af60c114f6297f3c069d44426" method="POST"
                accept-charset="UTF-8"
                id="inf_form_bc186b4af60c114f6297f3c069d44426"
                onsubmit="return false;"
                novalidate
            >
            -->
            <form
                class="form-horizontal infusion-form"
                action="https://nv681.infusionsoft.com/app/form/process/c0bdb426337e74253f273de1ac8d500e" method="POST"
                accept-charset="UTF-8"
                id="inf_form_c0bdb426337e74253f273de1ac8d500e"
                onsubmit="return false;"
                novalidate
            >
                @csrf
                <!--
                <input name="inf_form_xid" type="hidden" value="bc186b4af60c114f6297f3c069d44426" />
                <input name="inf_form_name" type="hidden" value="Pre-Launch &#a;Sign Up Form" />
                <input name="infusionsoft_version" type="hidden" value="1.70.0.114185" />
                -->
                <input name="inf_form_xid" type="hidden" value="c0bdb426337e74253f273de1ac8d500e" />
                <input name="inf_form_name" type="hidden" value="Browse page request&#a;Email, first name, last name" />
                <input name="infusionsoft_version" type="hidden" value="1.70.0.165163" />

                @include('layouts.flash_messages')
                <div
                    class="form-group"
                >
                    <div class="col-sm-12">
                        <input
                            type="text"
                            class="form-control input"
                            id="inf_field_FirstName"
                            name="inf_field_FirstName"
                            style="border-radius: 18px;"
                            placeholder="First Name"
                            required
                        />
                        <div class="invalid-feedback">
                            Please enter your first name.
                        </div>
                    </div>
                </div>
                <div
                    class="form-group"
                >
                    <div class="col-sm-12">
                        <input
                            type="text"
                            class="form-control input"
                            id="inf_field_LastName"
                            name="inf_field_LastName"
                            style="border-radius: 18px;"
                            placeholder="Last Name"
                            required
                        />
                    </div>
                </div>
                <div
                    class="form-group mb-0"
                >
                    <div class="col-sm-12">
                        <input
                            type="email"
                            class="form-control input"
                            id="inf_field_Email"
                            name="inf_field_Email"
                            style="border-radius: 18px;"
                            placeholder="Email"
                            required
                        >
                    </div>
                </div>
                <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="label" for="formGroupExampleInput2"></label>
                        <div>{!! app('captcha')->display(['data-callback' => 'recaptchaCallback']) !!}</div>
                    </div>
                </div>
                <div
                    class="form-group"
                >
                    <div class="col-sm-12">
                        <button
                            class="btn custom-primary btn-block infusion-recaptcha"
                            id="recaptcha_c0bdb426337e74253f273de1ac8d500e"
                            type="submit"
                            value="Submit"
                        >Explore Now!</button><br><br>
                    </div>
                </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="explore_submit" class="btn" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>
{!! NoCaptcha::renderJs() !!}
<script type="text/javascript">

    var captchaValidated = false;
    function recaptchaCallback() {
        captchaValidated = true;
    }
    $(() => {

        $('#modal-form').on('show.bs.modal', function (e) {
            $('#inf_form_c0bdb426337e74253f273de1ac8d500e').removeClass('was-validated');
            grecaptcha.reset();
            captchaValidated = false;
            $('#inf_form_c0bdb426337e74253f273de1ac8d500e')[0].reset();
        })

        $('#inf_form_c0bdb426337e74253f273de1ac8d500e').on('submit', () => {

            $('#inf_form_c0bdb426337e74253f273de1ac8d500e').addClass('was-validated');

            $.ajax({
                url: '/explore',
                type: 'POST',
                data: $( "#inf_form_c0bdb426337e74253f273de1ac8d500e" ).serialize(),
                success: function (data) {
                    // fb track complete registration
                    fbq('track', 'CompleteRegistration');
                    $('#inf_form_c0bdb426337e74253f273de1ac8d500e')[0].submit();
                },
                error: function(error) {
                    if(captchaValidated) {
                        grecaptcha.reset();
                    }
                }
            })
        });
    })
</script>
<script type="text/javascript" src="https://nv681.infusionsoft.app/app/webTracking/getTrackingCode"></script>
<script type="text/javascript" src="https://nv681.infusionsoft.com/resources/external/recaptcha/production/recaptcha.js?b=1.70.0.165163-hf-201911222327"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadInfusionRecaptchaCallback&render=explicit" async="async" defer="defer"></script>
<script type="text/javascript" src="https://nv681.infusionsoft.com/app/timezone/timezoneInputJs?xid=c0bdb426337e74253f273de1ac8d500e"></script>
@endsection
