@extends('layouts.layout')
@section('style')
    <style>

        .songwriter_sunday_school {
            padding: 40px 15px;
        }

        .socials {
            padding: 40px 15px;
        }

        .hero_headline {
            display: block;
            margin-bottom:20px;
            font-size: 39px;
        }

        .hero_headline_content {
            display: block;
            font-size: 14px;
            line-height: 30px;
            padding-top:0;
            text-align: justify;
        }

        .link-container {
            padding-top:0;
            position:relative;
            margin-top:-100px;
            padding-bottom: 20px;
        }

        #search_videos {
            width: 100%;
        }
        .user-videos-bg-image {
            background-position: 20px !important;
        }

        .w-padding {
            padding: 0 40px;
        }

        .search-container {
            padding-top: 20px;
        }

        #search_videos {
            margin-bottom: 20px;
        }

        .link-container a {
            font-weight: normal;
        }

        @media only screen and (max-width: 1200px) {
            .hero_headline {
                font-size: 35px;
            }

            .hero_headline_content {
                line-height: 25px;
            }

            .link-container {
                margin-top:-30px;
            }
        }

        @media only screen and (max-width: 992px) {

            .hero_headline {
                text-align: center;
                font-size: 35px;
            }

            .hero_headline_content {
                font-size: 14px;
                line-height: 30px;
            }

            .link-container {
                padding-top:0;
                position:relative;
                margin-top:50px;
                padding-bottom: 20px;
            }
        }

        @media only screen and (max-width : 768px) {
            .image-logo {
                display: block;
                margin: 0 auto;
            }

            .hero_headline {
                text-align: center;
                display: block;
            }

            .hero_headline_content {
                display: block;
                text-align: justify;
            }

            .w-padding {
                padding: 10px;
            }

            .socials {
                padding: 0;
                margin-bottom: 15px;
            }

            .hero_headline_content {
                margin-left: 0;
            }

            .button_group {
                font-size: 30px;
            }

            #search_videos {
                width: 100%;
            }
        }

        @media only screen and (max-width : 575px) {
            .hero_headline {
                font-size: 38px;
                margin-left: 0;
                margin-bottom: 20px;
            }
        }


    </style>
@endsection
@section('content')
<index-instrumental-component inline-template>
<div>
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
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-6 songwriter_sunday_school">
                    <img src="{{ asset('img/songwriterlogo-2.png') }}" class="image-logo" />
                </div>
                <div class="col-md-6 col-lg-6 socials">
                    <div class="text-center text-md-right">
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
            </div>
        </div>
    </div>
    <div class="main">
        <div class="container">
            <div class="row bg">
                <div class="col-xl-6 col-lg-7 col-md-12">
                    <span class="hero_headline">
                        Sing Along
                    </span>
                    <span class="hero_headline_content">
                            Instrumentals and karaoke videos are available! Here you’ll find all the things you need to sing along to your favorite songs!<br><br>
                            For each song, you stream a video in a high or low key to fit your voice range. If you already know the words, you can also just simply stream the instrumental audio files from device.
                    </span>
                </div>
                <div class="col-xl-6 col-lg-5 d-xl-block d-lg-block d-none">
                    <img src="/img/mclean.png" class="img-fluid">
                </div>
            </div>
            <div class="row link-container">
                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-2 offset-md-1 offset-sm-1">
                    <div class="row">
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12">
                            <a
                                role="button"
                                id="videos"
                                href="/videos"
                                class="btn btn-block btn_round btn_default"
                            >Videos</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="musics"
                                href="/songs"
                                class="btn btn-block btn_round btn_default"
                            >Music</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="sheet_musics"
                                href="/sheet_musics"
                                class="btn btn-block btn_round btn_default"
                            >Sheet Music</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="instrumentals"
                                href="/instrumentals"
                                class="btn btn-block btn_round btn_default active"
                            >Sing Along</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="podcasts"
                                href="/podcasts"
                                class="btn btn-block btn_round btn_default"
                            >Podcasts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="padding-bottom: 65px; padding-top: 20px;">
            <div class="col-lg-3 col-md-12 col-sm-12 offset-lg-9">
            <form
                class="form-inline my-2 my-lg-0"
                @submit.prevent="doSearch"
            >
                <input class="form-control mr-sm-2 search" name="s_fields" value="title" type="hidden" placeholder="Search" aria-label="Search">
                <input
                    class="form-control mr-sm-2 search"
                    name="search"
                    type="search"
                    placeholder="Search"
                    aria-label="Search"
                    v-model="search"
                />
            </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div
                    class="row"
                    v-if="instrumentals.length > 0"
                >
                    <div
                        class="col-lg-3 custom_col col-sm-12 col-xs-12 col-md-4"
                        v-for="(instrumental, index) in instrumentals"
                        :key="index"
                    >
                        <div class="card card_style" style="background-color: #d6d6d6;height:165px">
                            <div class="card-body">
                            </div>
                        </div>
                        <h6 class="title_date">
                            <small><b>
                                @{{ instrumental.title }} /
                                @{{ formatDate(instrumental.created_at) }}
                            </b></small>
                        </h6>
                        <div class="row" style="margin-left: 0; margin-right: 0">
                            <a
                                style="cursor: pointer;"
                                role="button"
                                data-toggle="modal"
                                data-target="#high_key_modal"
                                data-backdrop="static"
                                data-keyboard="false"
                                @click="showModal(instrumental, 'high')"
                            >
                                <h6 class="title_key" style="padding-top: 0 !important;">
                                    <small><b>HIGH KEY</b></small>
                                </h6>
                            </a>&nbsp;&nbsp;
                            <a
                                style="cursor: pointer;"
                                role="button"
                                data-toggle="modal"
                                data-target="#low_key_modal"
                                data-backdrop="static"
                                data-keyboard="false"
                                @click="showModal(instrumental, 'low')"
                            >
                                <h6 class="title_key" style="padding-top: 0 !important">
                                    <small><b>LOW KEY</b></small>
                                </h6>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" v-else>
                    <div class="card" style="background-color: #d6d6d6">
                        <div class="card-body text-center">
                            <b>No Instrumental Available</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div
                class="col-sm-10 offset-sm-2 col-md-10 offset-md-2 col-lg-2 offset-lg-10"
                v-if="instrumentals_next_page_url"
            >
                <button
                    role="button"
                    class="btn btn-default more"
                    @click="loadMoreInstrumentals"
                > MORE </button>
            </div>
        </div>
    </div>
    <footer class="footer" style="margin-top: 40px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                FOOTER
                </div>
            </div>
        </div>
    </footer>
</div>
</index-instrumental-component>
@endsection