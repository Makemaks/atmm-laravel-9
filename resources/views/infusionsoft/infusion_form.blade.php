<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>All Things Michael Mclean</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<style>
    @media only screen and (max-width: 990px) {
        .full-width-mobile {
            width: 100% !important;
            padding: 5px;
        }
        .heading-text {
            font-size: 20px !important;
        }
        td {
            width: 100% !important;
        }
        input {
            width: 100% !important;
        }
        button {
            width: 100% !important;
            border-radius: 18px !important;
        }
        .con-table {
            margin: 0px !important;
        }
    }
</style>
</head>
<body>
<!-- <form accept-charset="UTF-8" action="https://nv681.infusionsoft.com/app/form/process/bc186b4af60c114f6297f3c069d44426" class="infusion-form" id="inf_form_bc186b4af60c114f6297f3c069d44426" method="POST"> -->
        <!-- <form action="https://nv681.infusionsoft.com/app/form/process/bc186b4af60c114f6297f3c069d44426" method="POST" accept-charset="UTF-8" id="inf_form_bc186b4af60c114f6297f3c069d44426"> -->
    <form method="POST" accept-charset="UTF-8" id="inf_form_bc186b4af60c114f6297f3c069d44426">
    @csrf
    <input name="inf_form_xid" type="hidden" value="bc186b4af60c114f6297f3c069d44426" />
     <input name="inf_form_name" type="hidden" value="Pre-Launch &#a;Sign Up Form" />
     <input name="infusionsoft_version" type="hidden" value="1.70.0.114185" />
    <div class="col-md-12 full-width-mobile">
        <div>
            <table class="con-table">
                <tr>
                    <td>
                        <img src="img/songwriterlogo.png" class="logo_image">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 40%; padding-top: 40px;">
                        <p class="heading-text">
                            " Sometimes a song can teach us a truth the only way our hearts can hear it... "
                        </p>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <div style="width: 70%; padding-top: 5px;">
                            <p class="sub-heading-text">
                                Sign up now to receive email and updates to become a member of our Pre-Launch Team!
                            </p>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <div style="padding-top: 15%;">
                            @include('layouts.flash_messages')
                            <input class="input" id="inf_field_FirstName" name="inf_field_FirstName" placeholder="First Name" type="text" required/><br><br>
                            <input class="input" id="inf_field_LastName" name="inf_field_LastName" placeholder="Last Name" type="text" required/><br><br>
                            <input class="input" id="inf_field_Email" name="inf_field_Email" placeholder="Email" type="email" required/><br><br>

                            <!--
                            <div class="g-recaptcha" id="enq-recaptcha" data-sitekey="6LcTo7cUAAAAAMu-Rp7wvOAcByYyNfxNfn7rEnph"></div><br>
                            -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <!--
                            <button id="btnInfusionSoft" onclick="submitInfusionsoftForm()" class="btn-signup infusion-recaptcha" type="button" value="Submit">
                                Sign Up for Updates
                            </button>
                            -->
                            <br><br>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</body>
</html>
<script type="text/javascript" src="https://nv681.infusionsoft.app/app/webTracking/getTrackingCode"></script>
<script type="text/javascript" src="https://nv681.infusionsoft.com/app/timezone/timezoneInputJs?xid=bc186b4af60c114f6297f3c069d44426"></script>


<!-- recaptcha -->
<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
<script type="text/javascript">
  var CaptchaCallback = function() {
    $('.g-recaptcha').each(function(index, el) {
      grecaptcha.render(el, {'sitekey' : '6LcTo7cUAAAAAMu-Rp7wvOAcByYyNfxNfn7rEnph'});
    });
  };
</script>
 <script src="{{ asset('js/custom.js') }}"></script>