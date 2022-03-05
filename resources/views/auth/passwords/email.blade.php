@extends('layouts.layout')

@section('content')
<style>
.card-title-text {
    font-size: 30px;
    font-weight: bold;
    color: #4a4a4a;
}
.label {
    font-size: 17px;
    color: #696969;
}
</style>
@section('content')
<div style="background-image: linear-gradient(#dbdbdb, #fbfbfb);height: 100vh">
  <div class="container">
      <div class="row justify-content-center" style="padding-top: 135px;">
          <div class="col-md-8">
              <div class="card" style="background-color: #f1f1f1;border-radius:0">

                  <div class="card-body">

                      <div class="col-md-8">
                          <p class="card-title card-title-text">Reset Password</p>
                      </div>

                      @if (session('status'))
                          <div class="alert alert-success" role="alert">
                              {{ session('status') }}
                          </div>
                      @endif

                      <div class="col-md-12">
                          @include('layouts.flash_messages')
                      </div>

                      <form method="POST" action="{{ route('password.email') }}">
                          @csrf

                          <div class="row mb-3">
                              <label for="email" class="label col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                              <div class="col-md-8">
                                  @php /*
                                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                  */ @endphp
                                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                  @error('email')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <div class="col-md-9 offset-md-3">
                                  <button type="submit" class="btn explore">
                                      {{ __('Send Password Reset Link') }}
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
