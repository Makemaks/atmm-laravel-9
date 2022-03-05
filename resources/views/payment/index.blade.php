@extends('layouts.layout')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css" integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous">
<style>
a:hover {
    color: #fff;
}
a:focus {
    color: #fff;
}
.card_style {
    width: 100%;
    border-radius: 0;
    background: transparent!important;
    text-align: center;
}
.card-title {
    font-weight: bold;
    color: #fff;
    margin-bottom: 0;
    padding-top: 35px;
}
p.card-text {
    font-size: 21px;
    color: #fff;
    padding-top: 35px;
}
.explore {
    width: auto !important;
    background-color: #009688;
    color: #fff;
    border-radius: 999px;
    padding: 1rem 6.5rem;
    font-size: 25px;
}
.explore:hover {
    color: #fff;
    background-color: #008679;
    border-color: #008679;
}
.list {
    font-size: 22px;
    line-height: 2em;
    color: #4a4a4a;
    padding-top: 12px;
}
.sign_up {
    color: #4a4a4a;
}
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0) !important;
}
.btn {
    text-transform: none;
}
.form-control {
    line-height: unset !important;
}
body {
    background-color: #fff;
}
</style>
<div class="container-fluid">
    <div class="row" style="margin-left: 0">
        <div class="col-md-10 col--sm-12 offset-md-1 songwriter_sunday_school">
            <div class="row">
                ALL THINGS 
            </div>
            <div class="row">
                MICHAEL
            </div>
            <div class="row">
                MCLEAN
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 10px">
        <div class="col-lg-4 offset-lg-4 col-md-12 col-sm-12">
            <div class="card" style="background-color: #9b9b9b;height: 300px; border-radius:0">
                <div class="card-body">
                    <h5 class="card-title card_style">PURCHASE SUMMARY</h5>
                    <p class="card-text">1 Month Subscription &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $12</p>
                    <p class="card-text" style="padding-top: 0">
                        Purchase Total: 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        $12
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 100px;">
        <div class="col-md-5 offset-md-4">
            <h1 style="font-size: 3rem;">
                <i class="fas fa-shield-alt"></i> Payment Method
            </h1>
        </div>
    </div>
    <div class="row" style="padding-top: 100px">
        <div class="col-lg-6 offset-lg-3 col-md-12 col-sm-12">
            <form>
                <div class="form-row" style="padding-bottom: 50px;">
                    <div class="form-group col-md-5 offset-md-1">
                        <div class="radio">
                            <label>
                                <input type="radio" class="form-control" name="optionsRadios" id="optionsRadios1" value="option1">
                                <span class="bmd-radio"><div class="ripple-container"></div></span>
                                &nbsp; <img src="{{ asset('svg/Paypal.svg') }}">
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="radio">
                            <label>
                                <input type="radio" class="form-control" name="optionsRadios" id="optionsRadios2" value="option2">
                                <span class="bmd-radio"><div class="ripple-container"></div></span>
                                &nbsp; <img src="{{ asset('svg/visa.svg') }}" width="50">
                                <img src="{{ asset('svg/mastercard.svg') }}" width="50">
                                <img src="{{ asset('svg/american_express.svg') }}" width="50">
                                <img src="{{ asset('svg/discover_network.svg') }}" width="50">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" id="formGroupExampleInput" required placeholder="First Name*">
                    </div>
                    <div class="form-group col-md-6">
                        <input type="password" class="form-control" id="inputPassword4" required placeholder="Last Name*">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <input type="text" class="form-control" id="formGroupExampleInput" required placeholder="Card Number*">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="password" class="form-control" id="inputPassword4" required placeholder="CVV*">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Month">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="password" class="form-control" id="inputPassword4" required placeholder="Year*">
                    </div>
                    <div class="form-group col-md-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox">
                                <span class="checkbox-decorator"><span class="check"></span><div class="ripple-container"></div></span>
                                Save this card
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" id="formGroupExampleInput" required placeholder="Street Address*">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <select class="form-control" id="exampleFormControlSelect1" required>
                            <option selected>Country*</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <select class="form-control" id="exampleFormControlSelect1" required>
                            <option selected>State*</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="password" class="form-control" id="inputPassword4" required placeholder="Zip/Postal*">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4 offset-md-2" style="padding-top:100px">
                        <button type="submit" class="btn explore">Process Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row" style = "padding-top: 15%">
        <div class="col-sm-12 col-md-12">
            <div class="footer"> FOOTER </div>
        </div>
    </div>
</div>
@endsection