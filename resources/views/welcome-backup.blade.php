@extends('layouts.layout-full-screen')
@section('title', 'Login')

@section('content')
<div
    class="container-fluid explore-bg-image"
>
    <div>
        <div class="row" style="margin-right: 0">
            <div class="col-sm-12 songwriter_sunday_school">
                <img src="{{ asset('img/songwriterlogo-2.png') }}" class="image-logo" />
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <span class="header-text">
                        "Sometimes a song can teach us a truth the only way
                        our hearts can hear it..."
                    </span>
                    <br />
                    <span>
                        Click the <a href="#" class="custom-link">Explore</a>
                        button below and get an exclusive look at all amazing
                        content that will be available to you once you
                        <a href="#" class="custom-link">become a member</a>.
                        Check it out!
                    </span>
                    <br />
                    <a
                        href="#"
                        data-toggle="modal"
                        data-target="#modal-form"
                        role="button"
                        class="btn custom-primary white--text mt-3"
                        style="min-width: 200px;"
                    > Explore </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="padding-left: 25px; padding-right: 25px;">
        <div class="row" style="padding-top: 65px;padding-bottom: 65px;">
            <div
                class="col-lg-6 custom_col col-sm-12 col-xs-12 col-md-6"
            >
                <center>
                    <div
                        class="become-a-member-container"
                    >
                        <h5><b>Unlimited Access</b></h5>
                        <ul style="text-align: left;">
                            <li style="padding-left: 15px;">
                                Stream
                                <a href="#" class="custom-link">Michael McLeans</a>
                                complete music colletion (50+ albums)
                            </li>
                            <li style="padding-left: 15px;">Sheet music library</li>
                            <li style="padding-left: 15px;">
                                Stream karaoke/instrumental videos of every song,
                                PLUS sing along audio tracks
                            </li>
                            <li style="padding-left: 15px;">
                                Watch exclusive, uplifting video messages from Michael McLean
                                (new videos delivered Monday, Wednesday, and Friday)
                            </li>
                        </ul>
                        <a
                            role="button"
                            href="/select_payment"
                            class="btn custom-primary white--text"
                            style="min-width: 200px;"
                        > Become a Member </a>
                    </div>
                </center>
            </div>
            <div class="col-lg-6 custom_col col-sm-12 col-xs-12 col-md-6">
                <center>
                    <div class="become-a-member-container">
                        <h5><b>Sign in</b></h5>
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
                            <div>
                                <center>
                                    <button
                                        type="submit"
                                        class="btn custom-primary white--text"
                                        style="min-width: 200px;"
                                    >
                                            {{ __('Login') }}
                                    </button>
                                    <br />
                                    <br />
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" style="width:50%; color: black;" href="{{ route('password.request') }}">
                                            {{ __('Forgot username or password') }}
                                        </a>
                                    @endif
                                </center>
                            </div>
                        </form>
                    </div>
                </center>
            </div>
        </div>
    </div>
    <div class="row" style = "padding-top: 30px">
        <div class="col-sm-12 col-md-12">
        <div class="footer"> FOOTER </div>
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
            <form
                class="form-horizontal"
                action="https://nv681.infusionsoft.com/app/form/process/bc186b4af60c114f6297f3c069d44426" method="POST"
                accept-charset="UTF-8"
                id="inf_form_bc186b4af60c114f6297f3c069d44426"
            >
                @csrf
                <input name="inf_form_xid" type="hidden" value="bc186b4af60c114f6297f3c069d44426" />
                <input name="inf_form_name" type="hidden" value="Pre-Launch &#a;Sign Up Form" />
                <input name="infusionsoft_version" type="hidden" value="1.70.0.114185" />
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
                    class="form-group"
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
                <div
                    class="form-group"
                >
                    <div class="col-sm-12">
                        <button
                            class="btn custom-primary white--text btn-block"
                            style="border-radius: 18px;"
                            type="submit"
                            value="Submit"
                        >Sign Up for Updates</button><br><br>
                    </div>
                </div>
            </form>
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>
</div>
@endsection