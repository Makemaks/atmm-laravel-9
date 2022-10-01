@extends('layouts.layout-full-screen')
@section('title', 'Videos')

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

        .video-category {
            cursor: pointer;
        }

        .video-category h3 {
            font-size: 24px;
        }

        .video-category .video-container {
            position: relative;
        }

        .video-category .video-container .bg-image {
            width: 100%;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 163px;
            border: 1px solid #ddd;
        }

        .video-category .video-container img {
            border: 1px solid #ddd;
        }

        .video-category .video-container video {
            display: none;
        }

        .video-category .video-container .text-details {
            position: relative;
        }

        .video-category .video-container .text-details p.title {
            font-weight: bold;
            font-size:13px;
            margin-top:5px;
            width: 85%;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .video-category .video-container .text-details span.duration {
            position: absolute;
            right: 5px;
            top: 1px;
            font-size: 13px;
            font-weight: bold;
        }

        .video-img {
            max-height: 163px;
            margin: 0 auto;
            background: #ddd;
        }

        .slick-arrow {
            top: 24%;
            background-color: #eee;
            padding: 45px 7px;
            margin-top: -50px;
            border-radius: 100px;
            height: 166px;
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

            .has-search .form-control {
                height: 33px;
                font-size: 13px;
            }

            .has-search .form-control-feedback {
                top: -2px;
                font-size: 13px;
            }
        }

        @media only screen and (max-width : 768px) {
            .has-search .form-control {
                margin: 0 auto;
                width: 95%;
                height: 38px;
            }
        }

        @media only screen and (max-width : 575px) {

        }
        .loader {
          border: 16px solid #f3f3f3; /* Light grey */
          border-top: 16px solid #3498db; /* Blue */
          border-radius: 50%;
          width: 120px;
          height: 120px;
          animation: spin 2s linear infinite;
          margin:auto;
          left:0;
          right:0;
          top:0;
          bottom:0;
          position:fixed;
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
    </style>
@endsection
@section('content')
<index-video-component inline-template>
<div class="container">
  <div class="container">
  <div id = "myDiv">
    <h1 style="text-align: center">Please wait for the videos to load ....</h1>
    <div class="loader">
    </div>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  </div>
    <div class="banner">
        <div class="row">
            <div class="col-md-6">
                <span class="hero_headline">
                    Videos
                </span>
                <span class="hero_headline_content">
                    Here on All Things Michael Mclean I’m going to tell every
                    story and sing every song. I will be adding new video
                    content every week to brighten your day!
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
                                placeholder="Search for a video"
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
        <div class="video-category">
            <h3>Firesides</h3>
            <div class="videos">
                <div class="row carousel">
                    <div class="col-md-4" v-for="fireSidesVideo in fireSides.data">
                        <div class="video-container"
                            data-toggle="modal"
                            data-backdrop='static'
                            data-keyboard="false"
                            @click="showVideoInModal(fireSidesVideo.title, '{{ env('AWS_URL') }}' + fireSidesVideo.video, (fireSidesVideo.image ? '{{ env('AWS_URL') }}' + fireSidesVideo.image : '/img/white-video-placeholder.jpg'))"
                          >
                            <div class="bg-image" :style="{ backgroundImage: 'url(' + (fireSidesVideo.image ? '{{ env('AWS_URL') }}' + fireSidesVideo.image : '/img/white-video-placeholder.jpg') + ')' }"></div>
                            <video width="100%" controls :[poster]="(fireSidesVideo.image ? '{{ env('AWS_URL') }}' + fireSidesVideo.image : '/img/white-video-placeholder.jpg')" class="video">
                            <source :src="'{{ env('AWS_URL') }}' + fireSidesVideo.video" type="video/mp4">
                            </video>
                            <div class="text-details">
                                <p class="title">@{{ fireSidesVideo.title }}</p>
                                <span class="duration">0:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center text-md-right" v-if="fireSides.next_page_url">
                <p @click="nextPage(fireSides.next_page_url, 'fireSides')" style="cursor: pointer;font-weight: bold;">See More</p>
            </div>
        </div>
        <div class="video-category">
                <h3>All Things Michael Mclean</h3>
                <div class="videos">
                    <div class="row carousel">
                        <div class="col-md-4" v-for="songwriterSundaySchoolVideo in songwriterSundaySchool.data">
                            <div class="video-container"
                                data-toggle="modal"
                                data-backdrop='static'
                                data-keyboard="false"
                                @click="showVideoInModal(songwriterSundaySchoolVideo.title, '{{ env('AWS_URL') }}' + songwriterSundaySchoolVideo.video, (songwriterSundaySchoolVideo.image ? '{{ env('AWS_URL') }}' + songwriterSundaySchoolVideo.image : '/img/dark-video-placeholder.jpg'))"
                            >
                                <div class="bg-image" :style="{ backgroundImage: 'url(' + (songwriterSundaySchoolVideo.image ? '{{ env('AWS_URL') }}' + songwriterSundaySchoolVideo.image : '/img/dark-video-placeholder.jpg') + ')' }"></div>
                                <video width="100%" controls :[poster]="(songwriterSundaySchoolVideo.image ? '{{ env('AWS_URL') }}' + songwriterSundaySchoolVideo.image : '/img/dark-video-placeholder.jpg')" class="video">
                                <source :src="'{{ env('AWS_URL') }}' + songwriterSundaySchoolVideo.video" type="video/mp4">
                                </video>
                                <div class="text-details">
                                    <p class="title">@{{ songwriterSundaySchoolVideo.title }}</p>
                                    <span class="duration">0:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center text-md-right" v-if="songwriterSundaySchool.next_page_url">
                    <p @click="nextPage(songwriterSundaySchool.next_page_url, 'songwriterSundaySchool')" style="cursor: pointer;font-weight: bold;">See More</p>
                </div>
            </div>
        <div class="video-category">
            <h3>Memory Lane</h3>
            <div class="videos">
                <div class="row carousel">
                    <div class="col-md-4" v-for="memoryLaneVideo in memoryLane.data">
                        <div class="video-container"
                            data-toggle="modal"
                            data-backdrop='static'
                            data-keyboard="false"
                            @click="showVideoInModal(memoryLaneVideo.title, '{{ env('AWS_URL') }}' + memoryLaneVideo.video, (memoryLaneVideo.image ? '{{ env('AWS_URL') }}' + memoryLaneVideo.image : '/img/white-video-placeholder.jpg'))"
                            >
                            <div class="bg-image" :style="{ backgroundImage: 'url(' + (memoryLaneVideo.image ? '{{ env('AWS_URL') }}' + memoryLaneVideo.image : '/img/white-video-placeholder.jpg') + ')' }"></div>
                            <video width="100%" controls :[poster]="(memoryLaneVideo.image ? '{{ env('AWS_URL') }}' + memoryLaneVideo.image : '/img/white-video-placeholder.jpg')" class="video">
                            <source :src="'{{ env('AWS_URL') }}' + memoryLaneVideo.video" type="video/mp4">
                            </video>
                            <div class="text-details">
                                <p class="title">@{{ memoryLaneVideo.title }}</p>
                                <span class="duration">0:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center text-md-right" v-if="memoryLane.next_page_url">
                <p @click="nextPage(memoryLane.next_page_url, 'memoryLane')" style="cursor: pointer;font-weight: bold;">See More</p>
            </div>
        </div>
        <div class="video-category">
            <h3>Miscellaneous Michael</h3>
            <div class="videos">
                <div class="row carousel">
                    <div class="col-md-4" v-for="miscellaneousMichaelVideo in miscellaneousMichael.data">
                        <div class="video-container"
                            data-toggle="modal"
                            data-backdrop='static'
                            data-keyboard="false"
                            @click="showVideoInModal(miscellaneousMichaelVideo.title, '{{ env('AWS_URL') }}' + miscellaneousMichaelVideo.video, (miscellaneousMichaelVideo.image ? '{{ env('AWS_URL') }}' + miscellaneousMichaelVideo.image : '/img/dark-video-placeholder.jpg'))"
                          >
                            <div class="bg-image" :style="{ backgroundImage: 'url(' + (miscellaneousMichaelVideo.image ? '{{ env('AWS_URL') }}' + miscellaneousMichaelVideo.image : '/img/dark-video-placeholder.jpg') + ')' }"></div>
                            <video width="100%" controls :[poster]="(miscellaneousMichaelVideo.image ? '{{ env('AWS_URL') }}' + miscellaneousMichaelVideo.image : '/img/dark-video-placeholder.jpg')" class="video">
                            <source :src="'{{ env('AWS_URL') }}' + miscellaneousMichaelVideo.video" type="video/mp4">
                            </video>
                            <div class="text-details">
                                <p class="title">@{{ miscellaneousMichaelVideo.title }}</p>
                                <span class="duration">0:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center text-md-right" v-if="miscellaneousMichael.next_page_url">
                <p @click="nextPage(miscellaneousMichael.next_page_url, 'miscellaneousMichael')" style="cursor: pointer;font-weight: bold;">See More</p>
            </div>
        </div>
    </div>
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">...</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
</div>

<script type = "text/javascript">
  setTimeout(function(){
    document.getElementById("myDiv").style.display="none";
  }, 10000);
</script>
</index-video-component>
@endsection
