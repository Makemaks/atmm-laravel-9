<?php
    function getVideoPlaceholder($video) {
        $imageName = '';

        switch ($video->video_category_id) {
            case 2:
            case 3:
                $imageName = '/img/white-video-placeholder.jpg';
                break;

            case 4:
            case 6:
                $imageName = '/img/dark-video-placeholder.jpg';
                break;
        }

        return $imageName;
    }
?>
@extends('layouts.layout-full-screen')
@section('title', 'Explore')
@section('style')
    <style>

        .btn_default {
            background-color: #EEEEF0;
            border-radius: 999px;
            font-weight: bold;
        }
        .image-logo {
            display: block;
            height: 100px;
        }

        #welcome-video {
            border-radius: 3px;
            object-fit: cover;
        }

        .footer {
            font-size: 16px;
            color: #474747;
        }

        @media screen and (max-width: 480px) {
            .image-logo {
                height: 80px;
            }
        }

        .tab-link.btn_default:focus {
            background-color: #5ba6f0;
        }
        .tab-link.btn:focus {
            box-shadow: none;
        }

        .media-box {
            cursor: pointer;
            background-color: #d6d6d6;
            border-radius: .25em;
            height:165px;
            text-align: center;
            position: relative;
            background-size: cover;
            background-repeat: no-repeat;
            border: 1px solid #ddd;
            max-width: 271px;
            margin: 0 auto;
            background-position: center center;
        }

        .media-box.clear {
            background: #fff;
            border: 1px solid #ddd;
        }

        .media-box i {
            color: #fff;
            font-size: 80px;
            margin-top: 41px;
            -webkit-transition: color 200ms linear;
            -ms-transition: color 200ms linear;
            transition: color 200ms linear;
        }

        .media-box:hover i {
            color: #5ba6f0;
        }

        .media-box img {
            position: absolute;
            display: block;
            margin:0 auto;
            top:50%;
            left:50%;
            max-width: 100%;
            max-height: 100%;
        }

        .custom_col h5 {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            cursor: pointer;
        }

        .custom_col p{
            cursor: pointer;
            font-size: 13px;
        }

        #sheetMusicBlockModal .pointer {
            font-size: 50px;
            position: absolute;
            top: 45%;
            background: #fff;
            cursor: pointer;
        }

        #sheetMusicBlockModal .pointer.fa-angle-right{
            right: 3px;
        }

        #sheetMusicBlockModal .pointer.fa-angle-left{
            left: 3px;
        }

        #sheetMusicBlockModal .modal-body {
            padding: 1.5rem 2.1rem;
        }

        .high-key, .low-key {
            height: 165px;
            width: 50%;
            position: absolute;
        }

        .high-key {
            left: 0;
        }

        .low-key {
            right: 0;
        }
    </style>
@endsection
@section('content')
<div class="container-fluid explore-bg-image">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <video width="100%" controls autoplay="autoplay" id="welcome-video" poster="/img/explore_poster.jpg">
                    @if(!empty($exploreVideo))
                    <source src="{{ env('AWS_URL') . $exploreVideo->video }}" type="video/mp4">
                    @endif
                </video>
            </div>
        </div>
        {{-- <div class="row text-center" style="margin-top:40px;">
            <div class="offset-lg-3 offset-md-3 offset-md-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <span class="header-text">
                    "Sometimes a song can teach us a truth the only way
                    our hearts can hear it..."
                </span>
                <br />
                <span>
                    Sign up now to receive the latest and greatest on
                    Michael Mcleans legacy project and become a VIP
                    launch team member.
                </span>
                <br />
                <a
                    role="button"
                    href="/select_payment"
                    class="btn custom-primary white--text mt-3"
                    style="min-width: 200px;"
                > Explore </a>
            </div>
        </div> --}}
    </div>
    <div class="container">
        <div class="row text-center" style="margin-top: 40px">
            <div class="col-lg-12">
                <a
                    role="button"
                    href="/select_payment"
                    class="btn custom-primary white--text"
                    style="width:100%; max-width:450px; padding: 30px 0;padding: 12px 0px;
                    font-size: 20px;"
                > Subscribe </a>
                <?php // > Become a Member </a> */ ?>
            </div>
        </div>
        <div class="row" style="margin: 50px -15px;">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 offset-lg-1 offset-md-1 offset-sm-0 offset-xs-0">
                <div class="row">
                    <div class="col-lg-2 offset-lg-1 custom_col col-sm-12 col-md-12">
                        <a
                            {{-- href="/videos" --}}
                            href="#pills-video"
                            role="button"
                            id="videos"
                            class="tab-link btn btn-block btn_round btn_default"
                            data-toggle="pill"
                        >Videos</a>
                    </div>
                    <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                        <a
                            {{-- href="/sheet_musics" --}}
                            href="#pills-sheet-music"
                            role="button"
                            id="sheet_musics"
                            class="tab-link btn btn-block btn_round btn_default"
                            data-toggle="pill"
                        >Sheet Music</a>
                    </div>
                    <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                        <a
                            {{-- href="/instrumentals" --}}
                            href="#pills-instrumental"
                            role="button"
                            id="instrumentals"
                            class="tab-link btn btn-block btn_round btn_default"
                            data-toggle="pill"
                        >Sing Along</a>
                    </div>
                    <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                        <a
                            {{-- href="/podcasts" --}}
                            href="#pills-podcasts"
                            role="button"
                            id="podcasts"
                            class="tab-link btn btn-block btn_round btn_default"
                            data-toggle="pill"
                        >Podcasts</a>
                    </div>
                    <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                        <a
                            {{-- href="/music" --}}
                            href="#pills-music"
                            role="button"
                            id="musics"
                            class="tab-link btn btn-block btn_round btn_default"
                            data-toggle="pill"
                        >Music</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-video" role="tabpanel" aria-labelledby="pills-video">
                <div class="row" id="explore_videos">
                    @if(count($mediaSampleData['videos']) > 0)
                        @foreach ($mediaSampleData['videos'] as $video)
                            @php
                                $videoPlaceholder = getVideoPlaceholder($video);
                                $videoImage = $video->image ? (\Config::get('filesystems.aws_url') . $video->image) : $videoPlaceholder;
                            @endphp
                            <div class="col-lg-3 custom_col col-sm-12 col-md-12 video-block"
                                data-title="{{ $video->title }}"
                                data-video="{{ $video->video }}"
                                data-poster="{{ $videoImage }}"
                            >
                                <div class="media-box" style="background-image: url({{ $videoImage }})">
                                </div>
                                <h5>{{ $video->title }}</h5>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="pills-music" role="tabpanel" aria-labelledby="pills-music">
                <div class="row" id="sample_music">
                    @if(count($mediaSampleData['music']) > 0)
                        @foreach ($mediaSampleData['music'] as $music)
                            <div class="col-lg-3 custom_col col-sm-12 col-md-12 audio-block"
                                data-title="{{ $music->title }}"
                                data-audio="{{ $music->audio }}"
                            >
                                <div class="media-box clear">
                                    @if($music->thumbnail)
                                    <img class="img-fluid" src="{{ \Config::get('filesystems.aws_url') . $music->thumbnail}}" />
                                    @else
                                    <i class="fas fa-volume-up"></i>
                                    @endif
                                </div>
                                <h5>{{ $music->title }}</h5>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="pills-sheet-music" role="tabpanel" aria-labelledby="pills-sheet-music">
                <div class="row" id="sample_sheet_music">
                    @if(count($mediaSampleData['sheetMusic']) > 0)
                        @foreach ($mediaSampleData['sheetMusic'] as $sheetMusic)
                            <div class="col-lg-3 custom_col col-sm-12 col-md-12 sheet-music-block"
                                data-title="{{ $sheetMusic->title }}"
                                data-file="{{ $sheetMusic->file }}">
                                <div class="media-box">
                                    @if($sheetMusic->thumbnail)
                                    <img class="img-fluid" src="{{ \Config::get('filesystems.aws_url') . $sheetMusic->thumbnail}}" />
                                    @else
                                    <i class="fab fa-itunes-note"></i>
                                    @endif
                                </div>
                                <h5>{{ $sheetMusic->title }}</h5>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="modal fade" id="sheetMusicBlockModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">...</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <i class="fas fa-angle-left pointer" style="position: "></i>
                            <i class="fas fa-angle-right pointer"></i>
                            {{-- <object style="width:100%;height:500px;" type="application/pdf" frameborder="0"></object> --}}
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-instrumental" role="tabpanel" aria-labelledby="pills-instrumental">
                <index-instrumental-component explore-instrumentals='{!! str_replace("'", "&apos;", json_encode($mediaSampleData['instrumental'])) !!}' inline-template>
                    <div>
                        <div class="row" id="sample_instrumental">
                            @if(count($mediaSampleData['instrumental']) > 0)
                                <div class="col-lg-3 custom_col col-sm-12 col-md-12"
                                    v-for="(instrumental, index) in instrumentals"
                                    :key="index">
                                    <div class="media-box" style="line-height: 0; background-image: url(/img/instrumentals.jpg);cursor: default;">
                                        <a
                                            class="high-key"
                                            alt=""
                                            role="button"
                                            data-toggle="modal"
                                            data-target="#high_key_modal"
                                            data-backdrop="static"
                                            data-keyboard="false"
                                        @click="showModal(instrumental, 'high')"></a>
                                        <a
                                            class="low-key"
                                            alt=""
                                            role="button"
                                            data-toggle="modal"
                                            data-target="#low_key_modal"
                                            data-backdrop="static"
                                            data-keyboard="false"
                                        @click="showModal(instrumental, 'low')"></a>
                                    </div>
                                    <h5>@{{ instrumental.title }}</p>
                                </div>
                            @endif
                        </div>
                        <div
                            class="modal fade"
                            id="high_key_modal"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="exampleModalLabel"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content" style="border:none">
                                    <div
                                        class="modal-header control-modal-header"
                                        style="border-bottom: none;"
                                    >
                                        <div
                                            style="background-color: white; padding: 5px; width: 100%;"
                                        >
                                            <span>
                                                @{{ selectedInstrumental.title }} /
                                                @{{ formatDate(selectedInstrumental.created_at) }}
                                            </span>
                                            <button
                                                type="button"
                                                class="close"
                                                data-dismiss="modal"
                                                @click="closeModal"
                                                style="margin-right: -5px;"
                                            >
                                                <i
                                                    class="fas fa-times"
                                                    style="font-size: 18px;"
                                                ></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="modal-body control-modal-body">
                                        <div
                                            class="card override-border-radius"
                                            style="height: 350px;"
                                            :style="isPlayVideo ? 'background-color: black;' : 'background-color: white;'"
                                        >
                                            <video
                                                v-if="isPlayVideo"
                                                id="high_key_vid"
                                                height="100%"
                                                :src="'{{\Config::get('filesystems.aws_url')}}' + selectedInstrumental.high_key_video"
                                                controls
                                            >
                                            </video>
                                            <audio
                                                style="bottom: 0px; position: absolute; width: 100%"
                                                v-if="isPlayAudio"
                                                id="high_key_audio"
                                                :src="'{{\Config::get('filesystems.aws_url')}}' + selectedInstrumental.high_key_audio"
                                                controls
                                            ></audio>
                                        </div>
                                    </div>
                                    <div
                                        class="modal-footer"
                                        style="background-color: #202020;border-top:none"
                                    >
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <button
                                                type="button"
                                                id="play_low_key_video"
                                                class="btn float-right"
                                                :class="isPlayVideo ? 'control-button-active' : 'control-button'"
                                                @click="playVideo"
                                            >PLAY VIDEO</button>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <button
                                                type="button"
                                                id="play_low_key_audio"
                                                class="btn control-button"
                                                @click="playAudio"
                                                :class="isPlayAudio ? 'control-button-active' : 'control-button'"
                                            >STREAM AUDIO</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="modal fade"
                            id="low_key_modal"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="exampleModalLabel"
                            aria-hidden="true"
                        >
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content" style="border:none;">
                                    <div
                                        class="modal-header control-modal-header"
                                        style="border-bottom: none;"
                                    >
                                        <div
                                            style="background-color: white; padding: 5px; width: 100%;"
                                        >
                                            <span>
                                                @{{ selectedInstrumental.title }} /
                                                @{{ formatDate(selectedInstrumental.created_at) }}
                                            </span>
                                            <button
                                                type="button"
                                                class="close"
                                                data-dismiss="modal"
                                                @click="closeModal"
                                                style="margin-right: -5px;"
                                            >
                                                <i
                                                    class="fas fa-times"
                                                    style="font-size: 18px;"
                                                ></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        class="modal-body control-modal-body"
                                    >
                                        <div
                                            class="card override-border-radius"
                                            style="height: 350px;"
                                            :style="isPlayVideo ? 'background-color: black;' : 'background-color: white;'"
                                        >
                                            <video
                                                v-if="isPlayVideo"
                                                id="low_key_vid"
                                                height="100%"
                                                :src="'{{\Config::get('filesystems.aws_url')}}' + selectedInstrumental.low_key_video"
                                                controls
                                            >
                                            </video>
                                            <audio
                                                style="bottom: 0px; position: absolute; width: 100%"
                                                v-if="isPlayAudio"
                                                id="low_key_audio"
                                                :src="'{{\Config::get('filesystems.aws_url')}}' + selectedInstrumental.low_key_audio"
                                                controls
                                            ></audio>
                                        </div>
                                    </div>
                                    <div
                                        class="modal-footer"
                                        style="background-color: #202020;border-top:none"
                                    >
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <button
                                                type="button"
                                                id="play_low_key_video"
                                                class="btn float-right"
                                                :class="isPlayVideo ? 'control-button-active' : 'control-button'"
                                                @click="playVideo"
                                            >Play Video</button>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <button
                                                type="button"
                                                id="play_low_key_audio"
                                                class="btn control-button"
                                                @click="playAudio"
                                                :class="isPlayAudio ? 'control-button-active' : 'control-button'"
                                            >Stream Audio</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </index-instrumental-component>
            </div>
            <div class="tab-pane fade" id="pills-podcasts" role="tabpanel" aria-labelledby="pills-podcast">
                <div class="row" id="sample_podcast">
                    @if(count($mediaSampleData['podcasts']) > 0)
                        @foreach ($mediaSampleData['podcasts'] as $podcast)
                            <div class="col-lg-3 custom_col col-sm-12 col-md-12 podcast-block"
                                data-title="{{ $podcast->title }}"
                                data-audio="{{ $podcast->audio }}"
                            >
                                <div class="media-box clear">
                                    <img class="img-fluid" src="/img/{{ $podcast->type == 'podcast' ? 'podcast' : 'audiobook' }}.jpg" alt="">
                                </div>
                                <h5>{{ $podcast->title }}</h5>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="modal fade" id="podcastBlockModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">...</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <audio style="width: 100%;" controls>
                                <source src="" type="audio/mpeg">
                            </audio>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="videoBlockModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">...</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <video width="100%" controls poster="/img/welcome-video-placeholder.png">
                <source src="" type="video/mp4">
            </video>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="audioBlockModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">...</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <audio style="width: 100%;" controls>
                <source src="" type="audio/mpeg">
            </audio>
        </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>

    const mediaBoxImgResize = () => {
        $('.media-box img').each(function(idx, elem) {
            const mediaBoxImg = $(elem);

            mediaBoxImg.css({
                'margin-top': `${-(mediaBoxImg.height() / 2)}px`,
                'margin-left': `${-(mediaBoxImg.width() / 2)}px`
            })
        });
    };

    $(() => {
        mediaBoxImgResize();
    })

    $(window).on('load', () => {
        mediaBoxImgResize();
    });

    $(window).resize(function() {
        mediaBoxImgResize();
    });

    $(document).ready(function () {

        $('#video1').click(function () {
            window.open('http://songwriter.test/select_payment','_blank');
        });

        $('#videos').addClass('active');

        $('.tab-link').on('click', function() {
            $('.tab-link').not(this).removeClass('active');
            $(this).tab('show');
            setTimeout(() => {
                mediaBoxImgResize();
            }, 200)

        });

        const videoPlayer = $('#videoBlockModal video')[0];

        $('.video-block').on('click', function() {

            // pause the welcome video if ever its played
            $('#welcome-video')[0].pause();

            $('#videoBlockModal').modal({
                keyboard: false,
                backdrop: 'static'
            });

            $('#videoBlockModal .modal-title').text($(this).data('title'));

            videoPlayer.src = `{{ \Config::get('filesystems.aws_url') }}${$(this).data('video')}`;
            videoPlayer.poster = $(this).data('poster');
            //videoPlayer.play();
        });

        $('#videoBlockModal').on('hide.bs.modal', () => {
            videoPlayer.pause();
        });

        const audioPlayer = $('#audioBlockModal audio')[0];

        $('.audio-block').on('click', function() {

            // pause the welcome video if ever its played
            $('#welcome-video')[0].pause();

            $('#audioBlockModal').modal({
                keyboard: false,
                backdrop: 'static'
            })

            $('#audioBlockModal .modal-title').text($(this).data('title'));

            audioPlayer.src = `{{ \Config::get('filesystems.aws_url') }}${$(this).data('audio')}`;
            //audioPlayer.play();
        });

        $('#audioBlockModal').on('hide.bs.modal', () => {
            audioPlayer.pause();
        });

        let currentSheetMusic;
        let totalSheetMusic = $('.sheet-music-block').length;

        const displaySheetMusic = (sheetMusicElem) => {

            $('#sheetMusicBlockModal .modal-body object').remove();

            const obj = document.createElement('object');
            $obj = $(obj);
            $obj.css({
                width: '100%',
                height: '500px'
            })
            $obj.attr('type', 'application/pdf');
            $obj.attr('frameborder', '0');
            $obj.attr('data', `{{ \Config::get('filesystems.aws_url') }}${sheetMusicElem.data('file')}#view=Fit&zoom=page-fit`);

            $('#sheetMusicBlockModal .modal-body').append($obj);

            $('#sheetMusicBlockModal .modal-title').text(sheetMusicElem.data('title'));

            currentSheetMusic = sheetMusicElem;

        };

        $('.sheet-music-block').on('click', function() {

            // pause the welcome video if ever its played
            $('#welcome-video')[0].pause();

            displaySheetMusic($(this));

            $('#sheetMusicBlockModal .modal-body').append($obj);

            $('#sheetMusicBlockModal').modal({
                keyboard: false,
                backdrop: 'static'
            })
        });

        $('#sheetMusicBlockModal').on('hide.bs.modal', () => {
            $('#sheetMusicBlockModal .modal-body object').remove();
        });

        $('#sheetMusicBlockModal .pointer.fa-angle-left').on('click', function() {
            const currentIndex = $('.sheet-music-block').index(currentSheetMusic);

            let prevSheetMusic;

            if(currentIndex === 0) {
                prevSheetMusic = $('.sheet-music-block').eq(totalSheetMusic - 1);
            } else {
                prevSheetMusic= currentSheetMusic.prev();
            }

            displaySheetMusic(prevSheetMusic);
        });

        $('#sheetMusicBlockModal .pointer.fa-angle-right').on('click', function() {
            const currentIndex = $('.sheet-music-block').index(currentSheetMusic);

            let nextSheetMusic;

            if(currentIndex === (totalSheetMusic - 1)) {
                nextSheetMusic = $('.sheet-music-block').eq(0);
            } else {
                nextSheetMusic= currentSheetMusic.next();
            }

            displaySheetMusic(nextSheetMusic);
        });

        const podcastAudioPlayer = $('#podcastBlockModal audio')[0];

        $('.podcast-block').on('click', function() {

            // pause the welcome video if ever its played
            $('#welcome-video')[0].pause();

            $('#podcastBlockModal').modal({
                keyboard: false,
                backdrop: 'static'
            })

            $('#podcastBlockModal .modal-title').text($(this).data('title'));

            podcastAudioPlayer.src = `{{ \Config::get('filesystems.aws_url') }}${$(this).data('audio')}`;
            podcastAudioPlayer.play();
        });

        $('#podcastBlockModal').on('hide.bs.modal', () => {
            podcastAudioPlayer.pause();
        })

    });

</script>
@endsection
