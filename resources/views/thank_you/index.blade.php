@extends('layouts.layout')
<link rel="stylesheet" type="text/css" href="css/thankyou.css">
@section('content')
<style>
a:hover {
    color: #fff;
}
a:focus {
    color: #fff;
}
p.text-style {
    font-size: 45px;
    color: #4a4a4a;
}
p.text-style-content {
    font-size: 21px;
    color: #7c7c7c;
    line-height: 2em;
}
.explore {
    width: 35%;
    margin-left:0;
}
</style>
<div class="contact-form">
    <img src="img/songwriterlogo.png" class="top-logo">
    <form action="/">
        <h2>Thank You For Signing Up!</h2>
        <hr class="hr-line" style="width: 80%;">
        <center><button type="submit" class="but">Enter Site</a></center>
        <hr class="hr-line" style="width: 50%">
    </form>
</div>
@endsection