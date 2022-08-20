@extends('layouts.layout')
@section('style')
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
}
a.active {
    background-color: #9b9b9b;
    color: #fff;
    font-weight: bold;
}
.custom_col {
    padding-right: 7px !important;
    padding-left: 7px !important;
    padding-bottom: 8px !important;
}
.ml-5 {
    margin-left: 3.5rem!important;
}
.custom_col_card {
    padding-right: 7px !important;
    padding-left: 7px !important;
    padding-bottom: 8px !important;
}
.btn_default {
    background-color: #d6d6d6;
    color: #4b4b4b;
    font-weight: bold;
}
.btn_default:hover {
    background-color: #9b9b9b;
    color: #fff;
    font-weight: bold;
}
#welcome_vid {
    -webkit-mask-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA5JREFUeNpiYGBgAAgwAAAEAAGbA+oJAAAAAElFTkSuQmCC);
    border-radius: 10px;
}
</style>
@endsection
@section('content')

<index-not-logged-in-component inline-template>
<div class="container-fluid">
    <div  style="background-image: linear-gradient(#dbdbdb, #fbfbfb);">
        <div class="row" style="margin-right: 0">
            <div class="col-sm-12 songwriter_sunday_school">
                <div class="row">
                    SONGWRITER
                </div>
                <div class="row">
                    SUNDAY
                </div>
                <div class="row">
                    SCHOOL
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <video id="welcome_vid" src="{{ asset('video/welcome.mp4') }}" width="100%" controls autoplay muted>
                </video>
            </div>
            <!-- <div class="col-lg-5 ml-5 mr-5">
                <div class="row hero_headline">
                    HERO HEADLINE
                </div>
                <div class="row hero_headline_content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                    nisi ut aliquip ex ea commodo consequat.
                </div>
                <div class="row hero_headline_explore" style = "padding-top: 30px">
                    <a role="button" href="/explore" class="btn explore"> Explore </a>
                </div>
            </div> -->
        </div>
        <div class="row text-center" style='padding-top: 15px'>
            <div class="col-lg-12">
                <a role="button" href="/select_payment" class="btn btn-lg explore"> Become a Member </a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="padding-top: 65px;padding-bottom: 65px;">
            <div class="col-lg-2 custom_col col-sm-12 col-md-12">
                <a href="/videos" role="button" id="videos" class="btn btn-block btn_round btn_default">VIDEOS</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a href="/musics" role="button" id="musics" class="btn btn-block btn_round btn_default">SONGS</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a href="albums" role="button" id="albums" class="btn btn-block btn_round btn_default">ALBUMS</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a href="/sheet_musics" role="button" id="sheet_musics" class="btn btn-block btn_round btn_default">SHEET MUSIC</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a href="/instrumentals" role="button" id="instrumentals" class="btn btn-block btn_round btn_default">INSTRUMENTAL</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a href="/podcasts" role="button" id="podcasts" class="btn btn-block btn_round btn_default">PODCASTS</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 custom_col col-sm-12 col-md-12">
                <video src="{{ asset('video/welcome.mp4') }}" width="100%" height="165px" controls>
                </video>
            </div>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12 ">
                <video src="{{ asset('video/welcome.mp4') }}" width="100%" height="165px" controls>
                </video>
            </div>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12 ">
                <video src="{{ asset('video/welcome.mp4') }}" width="100%" height="165px" controls>
                </video>
            </div>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12 ">
                <video src="{{ asset('video/welcome.mp4') }}" width="100%" height="165px" controls>
                </video>
            </div>
        </div>
        <div class="row" id='video1'>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12" >
                <video width="100%" height="165px" @play="transferToPayment()" controls>
                </video>
            </div>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12">
                <video width="100%" height="165px" @play="transferToPayment()" controls>
                </video>
            </div>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12">
                <video width="100%" height="165px" @play="transferToPayment()" controls>
                </video>
            </div>
            <div class="col-lg-3 custom_col col-sm-12 col-md-12">
                <video width="100%" height="165px" @play="transferToPayment()" controls>
                </video>
            </div>
        </div>
    </div>
    <div class="row" style = "padding-top: 15%">
        <div class="col-sm-12 col-md-12">
        <div class="footer"> FOOTER </div>
        </div>
    </div>
</div>
 <!--
<script>
    $(document).ready(function () {
        $('#video1').click(function () {
            window.open('http://songwriter.test/select_payment','_blank');
        });
        $('#instrumentals').addClass('active');
    });
</script>
-->

</index-not-logged-in-component>
@endsection
