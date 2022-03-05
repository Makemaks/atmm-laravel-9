@extends('layouts.layout-full-screen')
@section('title', 'Settings')
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
                Settings
            </span>
            <span class="hero_headline_content">
                    <!--
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                -->
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
                                style="font-weight: normal;"
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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card" style="background-color: #f1f1f1;border-radius:0">
                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="change-password-tab" data-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="true">Change Password</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="subscription-tab" data-toggle="tab" href="#subscription" role="tab" aria-controls="subscription" aria-selected="false">Subscription</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                            <form
                                style="padding-top: 20px;"
                                method="POST"
                                accept-charset="UTF-8"
                                id="frmChangePassword"
                                novalidate
                                onsubmit="return false;"
                            >
                                @csrf
                                @include('layouts.flash_messages')
                                <div id="change-password-result"></div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput">Email</label>
                                        <input type="text" name="email" value="{{Auth::user()->email}}" readonly required class="form-control" id="formGroupExampleInput" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput2">Current Password</label>
                                        <!--
                                        <input type="password" name="password" required class="form-control" id="formGroupExampleInput2" placeholder="Password">
                                        --->
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            required
                                            class="form-control"
                                            id="formGroupExampleInput2"
                                            placeholder="Password"
                                        />
                                        <div id="password-feedback" class="invalid-feedback">
                                           The password field is required.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput2">New Password</label>
                                        <input
                                            type="password"
                                            name="new_password"
                                            id="new_password"
                                            required class="form-control"
                                            placeholder="New Password"
                                        />
                                        <div id="new_password-feedback" class="invalid-feedback">
                                           The password field is required.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput2">Confirm Password</label>
                                        <input
                                            type="password"
                                            name="confirm_password"
                                            id="confirm_password"
                                            required
                                            class="form-control"
                                            placeholder="Confirm Password"
                                        />
                                        <div id="confirm_password-feedback" class="invalid-feedback">
                                           The password field is required.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </form>

                      </div>
                      <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                            <form
                                style="padding-top: 20px;"
                                method="POST"
                                accept-charset="UTF-8"
                                id="frmSubscription"
                                novalidate
                                onsubmit="return false;"
                            >
                                @csrf
                                @include('layouts.flash_messages')
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput2">Credit Card</label>
                                        @foreach ($credit_cards as $credit_card)
                                            <br>
                                            ID: {{$credit_card->id}} <br>
                                            Card Number: {{$credit_card->card_number}} {{$credit_card->card_type}} <br>
                                            Validation Status: {{$credit_card->validation_status}} <br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput2">Subscription Type</label>
                                        <input type="text" required class="form-control" disabled value="{{$subscription_type}}" name="subscription_type" id="subscription_type" >
                                        <input type="hidden" name="reason_to_stopped" id="reason_to_stopped">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label class="label" for="formGroupExampleInput2">Subscription Status</label>
                                        <input type="text" required class="form-control" disabled value="{{$inf_subscrip_status}}"  name="inf_subscrip_status" id="inf_subscrip_status" >
                                    </div>
                                </div>

                                @if ($inf_subscrip_status == 'Active')
                                <div class="form-group">

                                    <div class="col-md-10">
                                        <button id="btnCancelSubscription" type="submit" class="btn btn-danger">
                                        Cancel Subscription</button>
                                        <span id="loading_img"></span>
                                    </div>
                                </div>
                                @endif

                            </form>
                      </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '{{ config('app.url')}}';
    $(() => {

        $('#frmChangePassword').on('submit', () => {
            $('#frmChangePassword').addClass('was-validated');
            $.ajax({
                url: '/update_credential',
                type: 'POST',
                data: $( "#frmChangePassword" ).serialize(),
                success: function (json_data) {
                    var str_result = '<div class="alert alert-success alert-block"><button type="button" data-dismiss="alert" class="close">×</button> <strong>'+json_data.success+'</strong></div>'
                    $('#change-password-result').html(str_result);
                    $('#change-password-result').show();
                    $('.invalid-feedback').hide();
                    //$('.invalid-feedback').prop('valid', true);
                },
                error: function (request, status, error) {
                    $('#change-password-result').hide();
                    var errors = $.parseJSON(request.responseText);
                    $.each(errors, function (index, value) {
                        if( index == 'errors' ) {
                            $.each(value, function (ind, val) {
                                //$('#'+ind).attr('class', 'form-control is-invalid');
                                $('#'+ind+'-feedback').text(val);
                                $('#'+ind+'-feedback').show();
                            });
                        }
                    });
                }
            })
        });

        $('#frmSubscription').on('submit', () => {
            var resultprompt = prompt("Are you sure you want to cancel your subscription? Please provide a valid reason.", "");
            if (resultprompt != null &&  resultprompt != 'null' &&  resultprompt != "") {
                $('#reason_to_stopped').val(resultprompt);
                $('#btnCancelSubscription').attr('disabled');
                $('#loading_img').html('<img src="'+base_url+'/img/loading.gif" width="32">');
                $.ajax({
                    url: '/cancel-subscription',
                    type: 'POST',
                    data: $( "#frmSubscription" ).serialize(),
                    success: function (json_data) {
                        alert(json_data.message);
                        $('#btnCancelSubscription').hide();
                        $('#btnCancelSubscription').removeAttr('disabled');
                        $('#loading_img').html('');
                    },
                    error: function (request, status, error) {
                        $('#btnCancelSubscription').removeAttr('disabled');
                        $('#loading_img').html('');
                    }
                })
            } else {
                $('#reason_to_stopped').val('');
            }
        });

    })
</script>

@endsection