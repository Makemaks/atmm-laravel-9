@extends('layouts.layout')

@section('content')
<style>
a:hover {
    color: #fff;
}
a:focus {
    color: #fff;
}
a.feature {
    color: #4a4a4a;
}
a.feature:hover {
    color: #515151;
}
a.feature:focus {
    color: #515151;
}
.card_style {
    width: 100%;
    border-radius: 0;
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
.title_date {
    padding-top: 20px;
    font-weight: bold;
    color: #4b4b4b;
}
.col_padding {
    padding-bottom: 25px;
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
.btn_default:focus {
    background-color: #9b9b9b;
    color: #fff;
    font-weight: bold;
}
a.active {
    background-color: #9b9b9b;
    color: #fff;
    font-weight: bold;
}
.column_header {
    font-weight: bold;
    color: #4a4a4a;
    font-size: 17px;
}
.button_group {
    font-size: 35px;
    color: #999999;
}
.button_group:hover {
    font-size: 35px;
    color: #666;
}
.search {
    border-radius: 999px;
    background: #f1f1f1;
    border-color: #f1f1f1;
}
.songwriter_sunday_school {
    background-color: #d6d6d6 !important;
}
.albums {
    padding-bottom: 20px;
    padding-top: 15px;
    font-weight: bold;
    color: #4a4a4a;
}
.explore {
    width: 40%;
}
p.card-body-text {
    margin-top: 50px;
    font-weight: 700;
    color: #fff;
}
.table td, .table th {
    padding: .75rem;
    vertical-align: top;
    border-top: none !important;
}
button.btn.btn-play-pause {
    background-color: transparent;
    color: #b8e986;
}
.btn-no-focus:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0);
}
.more {
    color: #4d4d4dd4;
    font-weight: bold;
    font-size: 19px;
}
.more:hover {
    color: #515151;
}
.more:focus {
    color: #515151;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6 songwriter_sunday_school">
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
        <div class="col-sm-12 col-md-12 col-lg-6" style="padding: 40px 103px;background-image: linear-gradient(#dbdbdb, #fbfbfb);">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 ml-auto">
                    <div class="btn-group" aria-label="First group" >
                        <a role="button" href="/settings" class="btn btn-default button_group">
                            <i class="fas fa-cog"></i>
                        </a>
                        <a role="button" href="/feedbacks" class="btn btn-default button_group">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a role="button" href="/explore" class="btn btn-default button_group">
                            <i class="fab fa-facebook-square"></i>
                        </a>
                        <a role="button" href="/explore" class="btn btn-default button_group">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div id='title_section' style="padding-top: 32px; margin-left: -40px !important; padding-bottom: 100px;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="hero_headline">
                            FEATURE ALBUM
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="hero_headline">
                            <a href="#" role="button" class="btn explore">PLAY ALBUM</a>
                        </div>
                    </div>
                </div>
                <div id="feature_download" data-id="{{$display}}">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="hero_headline_content">
                                <a href="#" role="button" class="btn explore">PLAY SHUFFLE</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="hero_headline_content">
                                <a href="#" class="feature">DOWNLOAD SHEET MUSIC</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="padding-top: 10px;padding-bottom: 65px;">
            <div class="col-lg-2 custom_col col-sm-12 col-md-12">
                <a role="button" id="videos" href="/videos" class="btn btn-block btn_round btn_default">VIDEOS</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a role="button" id="musics" href="/musics" class="btn btn-block btn_round btn_default">SONGS</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a role="button" id="albums" href="/albums" class="btn btn-block btn_round btn_default">ALBUMS</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a role="button" id="sheet_musics" href="/sheet_musics" class="btn btn-block btn_round btn_default">SHEET MUSIC</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a role="button" id="instrumentals" href="/instrumentals" class="btn btn-block btn_round btn_default">INSTRUMENTAL</a>
            </div>
            <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                <a role="button" id="podcasts" href="/podcasts" class="btn btn-block btn_round btn_default">PODCASTS</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12 offset-lg-9 offset-md-8">
                <form class="form-inline my-2 my-lg-0">
                    @csrf
                    <input class="form-control mr-sm-2 search" type="hidden" name="s_fields" value="album,title" placeholder="Search" aria-label="Search">
                    <input class="form-control mr-sm-2 search" type="search" name="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
        </div>
        <div id="isSearchNull">
            <div class="row">
                <div class="col-md-12 albums">
                    <div class="float-left">
                        <h4>ALBUMS</h4>
                    </div>
                    <div class="float-right">
                        <div class="dropdown">
                            <button class="btn btn-no-focus dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent; font-weight: 700; color: #4a4a4a;">
                                SORT
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="/albums?sort=1">Featured</a>
                                <a class="dropdown-item" href="/albums?sort=2">Oldest</a>
                                <a class="dropdown-item" href="/albums?sort=3">Newest</a>
                                <a class="dropdown-item" href="/albums?sort=4">By Name</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($band_albums as $key => $band_album)
                <div class="col-lg-3 custom_col col-sm-12 col-md-12 text-center">
                    <a href="/album_details?id={{$band_album->id}}" style="text-decoration:none">
                        <div class="card card_style" style="background-color: #d6d6d6;height:165px;background-image:url({{'https://s3.ap-southeast-1.amazonaws.com/'.$band_album->image}});background-size:cover;background-repeat:no-repeat;">
                        <div class="card-body" style="background-color: #00000087;">
                                <p class="card-body-text">{{$band_album->album}}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div id="isSearch">
            <div class="row">
                <div class="col-lg-2 col-md-12 col-sm-12 albums">
                    <h4>RESULTS</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <tbody>
                            @foreach ($album_musics as $key => $album_music)
                            <tr>
                                <td style="background-color: #d6d6d6; width: 7%;"></td>
                                <td>{{$album_music->title}}</td>
                                <td>
                                    <center>
                                    <audio id="audio{{$album_music->id}}" data-id="{{$album_music->id}}" src="{{'https://s3.ap-southeast-1.amazonaws.com/'.$album_music->audio}}" style="height:30px">
                                    </audio>
                                    </center>
                                </td>
                                <td>
                                    <div class="float-right" style="color: #b8e986;">
                                        <button class="btn btn-play-pause" id="{{$album_music->id}}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 offset-sm-2 col-md-10 offset-md-2 col-lg-2 offset-lg-10">
            <a href="{{$band_albums->nextPageUrl()}}" role="button" class="btn btn-default more"> MORE </a>
        </div>
    </div>
    <div class="row" style = "padding-top: 15%">
        <div class="col-sm-12 col-md-12">
        <div class="footer"> FOOTER </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var play_pause = true;

        var albums = document.getElementById("albums");
        albums.className += " active";

        $('#isSearch').hide();
        // console.log($('#feature_download').data('id'));
        if ($('#feature_download').data('id')) {
            $('#feature_download').show();
            $('#title_section').css({ 'padding-bottom' : '0'});
            $('#isSearchNull').hide();
            $('#isSearch').show();
        } else {
            $('#isSearchNull').show();
            $('#isSearch').hide();
            $('#feature_download').hide();
            $('#title_section').css({ 'padding-bottom' : '100px'});
        }

        $('.btn-play-pause').click(function () {
            var play_pause_id = $(this).attr('id');
            if (play_pause) {
                $('#'+play_pause_id).find('i').removeClass('fa-play'); 
                $('#'+play_pause_id).find('i').addClass('fa-pause'); 
                play_pause = false;
                document.getElementById('audio'+play_pause_id).play(); 
            } else {
                $('#'+play_pause_id).find('i').removeClass('fa-pause');
                $('#'+play_pause_id).find('i').addClass('fa-play');
                play_pause = true;
                document.getElementById('audio'+play_pause_id).pause();
            }
        });

        document.addEventListener('play', function(e){
            var audios = document.getElementsByTagName('audio');
            for(var i = 0, len = audios.length; i < len;i++){
                if(audios[i] != e.target){
                    var play_pause_id = $(audios[i]).data('id');
                    audios[i].pause();
                    audios[i].currentTime = 0;
                    $('#'+play_pause_id).find('i').removeClass('fa-pause');
                    $('#'+play_pause_id).find('i').addClass('fa-play');
                }
            }
        }, true);
    });
    // var videos = document.getElementById("videos");
    // videos.classList.remove("active");

    // var sheet_musics = document.getElementById("sheet_musics");
    // sheet_musics.classList.remove("active");

    // var instrumentals = document.getElementById("instrumentals");
    // instrumentals.classList.remove("active");

    // var podcasts = document.getElementById("podcasts");
    // podcasts.classList.remove("active");

    // var musics = document.getElementById("musics");
    // musics.classList.remove("active");

    
</script>
@endsection