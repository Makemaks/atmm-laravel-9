@extends('layouts.layout-full-screen')
@section('title', 'Sing Along')

@section('style')
    <link rel="stylesheet" type="text/css" href="/css/media-page.css">
    <style>
      .search-container {
        margin-bottom:15px;
      }

      .instrumental {
        margin-bottom: 35px;
      }

      .instrumental .image-container {
        position: relative;
      }

      .instrumental .image-container a {
        position: absolute;
        height:100%;
      }

      .instrumental .image-container .high-key {
        width: 47.5%;
        left: 0;
        top: 0;
        cursor: pointer;
      }

      .instrumental .image-container .low-key {
        width: 47.5%;
        left: 52%;
        top: 0;
        cursor: pointer;
      }

      .instrumental .title {
        font-size:12px;
        font-weight: bold;
        margin-top:8px;
      }

        .slick-arrow {
            top: 28%;
            background-color: #eee;
            padding: 130px 7px;
            margin-top: -130px;
            border-radius: 100px;
            height: 395px;
        }

        .slick-arrow:hover {
            background-color: #aaa;
            color:#fff !important;
        }

        .slick-prev {
            left: -55px;
        }

        .slick-next {
            right: -55px;
        }

      @media (max-width: 1199.98px) {

      }

      @media (max-width: 991.98px) {
        .sheet .card {
          height:135px;
        }
      }

      @media only screen and (max-width : 768px) {
        .instrumental .title {
          text-align: center;
        }
      }

      @media only screen and (max-width : 575px) {

      }
    </style>
@endsection
@section('content')
<index-instrumental-component inline-template>
<div class="container">
    <div class="banner">
        <div class="row">
            <div class="col-md-6">
                <span class="hero_headline">
                    Sing Along
                </span>
                <span class="hero_headline_content">
                    Karaoke videos and audio files are available to stream
                    in both high and low keys. Click the icons to access them.<br/><br>
                </span>
            </div>
            <div class="col-md-6 d-xl-block d-lg-block d-md-block d-none position-relative">
                <img src="/img/mclean.png" class="img-fluid banner-image">
            </div>
        </div>
        @include('media-links')
        <div class="row">
            <div class="col-12">
                <div class="search-container">
                    <form
                        class="form-inline my-2 my-lg-0"
                        @submit.prevent="doSearch"
                    >
                        @csrf
                        <input class="form-control search" type="hidden" name="s_fields" value="title" placeholder="Search for a video" aria-label="Search">
                        <div class="form-group has-search">
                            <input
                                class="form-control search"
                                type="text"
                                name="search"
                                id="search_videos"
                                placeholder="Search"
                                aria-label="Search"
                                v-model="search"
                            />
                            <span class="fa fa-search form-control-feedback"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content-padding">
        <div class="instrumentals">
            <div class="row carousel" v-if="instrumentals.length > 0">
                <div
                  class="col-md-4"
                  v-for="(instrumental, index) in instrumentals"
                  :key="index">
                    <div class="instrumental">
                        <div class="image-container">
                            <!-- <img src="/img/instrumentals.jpg" class="img-fluid gray-border" alt=""> -->
                            <img :src="aws_url + instrumental.image" class="img-fluid gray-border" :alt="instrumental.title" :title="instrumental.title" />
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
                        <p class="title">@{{ instrumental.title }}</p>
                    </div>
                </div>
            </div>
        </div>
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
@endsection
