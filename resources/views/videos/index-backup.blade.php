@extends('layouts.layout-full-screen')
@section('title', 'Videos')

@section('style')
    <style>

        .songwriter_sunday_school {
            padding: 40px 15px;
        }

        header .socials {
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

        /* @media only screen and (max-width: 1199px) {
            .user-videos-bg-image {
                background-position: -650px !important;
            }

            .hero_headline_content {
                font-size: 19px !important;
            }
        } */

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

            header .socials {
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
<index-video-component inline-template>
<div>
    <div class="main">
        <div class="container">
            <div class="row bg">
                <div class="col-xl-6 col-lg-7 col-md-12">
                    <span class="hero_headline">
                        Videos
                    </span>
                    <span class="hero_headline_content">
                        Here on All Things Michael Mclean I’m going to tell every
                        story and sing every song. I will be adding new videos
                        three times a week that I hope will brighten your day!
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
                                class="btn btn-block btn_round btn_default active"
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
                                class="btn btn-block btn_round btn_default"
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
<div style="overflow: hidden;">
    <div>
        <div class="container">
            <div
                class="row search-container"
            >
                <div class="col-lg-3 col-md-4 col-sm-10 offset-lg-9 offset-md-8 offset-sm-1">
                <form
                    class="form-inline my-2 my-lg-0"
                    @submit.prevent="doSearch"
                >
                    @csrf
                    <input class="form-control mr-sm-2 search" type="hidden" name="s_fields" value="title" placeholder="Search" aria-label="Search">
                    <input
                        class="form-control mr-sm-2 search"
                        type="search"
                        name="search"
                        id="search_videos"
                        placeholder="Search"
                        aria-label="Search"
                        v-model="search"
                    />
                </form>
                </div>
            </div>

            <div
                class="row"
            >
                <div class="col-md-12">
                    <div class="col-md-12" style="padding-bottom: 30px;">
                        <div class="text-center">
                            <h4 class="column_header">Memory Lane</h4>
                        </div>
                    </div>
                    <carousel
                        :scroll-per-page="false"
                        :per-page-custom="[[480, 1], [768, 2], [900, 3]]"
                        :navigation-enabled="true"
                    >
                        <slide
                            v-for="(memory, index) in memories"
                            :key="index"
                        >
                            <center style="padding: 10px;">
                                <div
                                    style="height: 120px; background-color: black; border-radius: 3px;"
                                >
                                    <video
                                        height="100%"
                                        controls
                                        :src="'/view-video/' + memory.id"
                                        controlsList="nodownload"
                                    ></video>
                                </div>
                            </center>
                        </slide>
                    </carousel>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a role="button" class="btn btn-default more"> See All </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-12 col-md-12" style="padding-bottom: 30px;">
                        <div class="text-center">
                            <h4 class="column_header">All Things Michael Mclean</h4>
                        </div>
                    </div>
                    <carousel
                        :scroll-per-page="false"
                        :per-page-custom="[[480, 1], [768, 2], [900, 3]]"
                        :navigation-enabled="true"
                    >
                        <slide
                            v-for="(songwriter, index) in songwriters"
                            :key="index"
                        >
                            <center style="padding: 10px;">
                                <div
                                    style="height: 120px; background-color: black; border-radius: 3px;"
                                >
                                    <video
                                        height="100%"
                                        controls
                                        :src="'/view-video/' + songwriter.id"
                                        controlsList="nodownload"
                                    ></video>
                                </div>
                            </center>
                        </slide>
                    </carousel>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a role="button" class="btn btn-default more"> See All </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</index-video-component>
@endsection