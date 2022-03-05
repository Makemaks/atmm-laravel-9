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
<index-sheet-music-component inline-template>
    <div>
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
                            Sheet Music
                        </span>
                        <span class="hero_headline_content">
                                Sheet music is available! To access sheet music from browser, tablet, or mobile device, click the thumbnail preview and you can view the music from your device. <br><br>If you want to download and/or print, click the icons below the thumbnail.
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
                                    class="btn btn-block btn_round btn_default active"
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
        <div class="container">
            <div class="row" style="padding-bottom: 65px; padding-top: 20px;">
                <div class="col-lg-3 col-md-12 col-sm-12 offset-lg-9">
                <form
                    class="form-inline my-2 my-lg-0"
                    @submit.prevent="doSearchSheets"
                >
                    <input
                        class="form-control mr-sm-2 search"
                        type="text"
                        placeholder="Search"
                        aria-label="Search"
                        v-model="searchSheets"
                    />
                </form>
                </div>
            </div>
            <div class="row">
                <div
                    v-for="(sheet, index) in sheets"
                    :key="index"
                    class="col-lg-2 custom_col col-sm-12 col-md-12"
                    :class="(index+1)%5 == 1 || (index+1) == 1 ? 'offset-lg-1' : ''"
                >
                    <div class="card card_style" style="background-color: #d6d6d6;height:165px">
                        <div class="card-body">
                            <h6 class="card-title text-center card_title_style">
                                @{{ sheet.title }}
                            </h6>
                        </div>
                    </div>
                    <div class="float-right">
                        <div class="row" style="margin-left: 0; margin-right: 0">

                            <a
                                target="pdf-frame"
                                :href="'{{\Config::get('filesystems.aws_url')}}' + sheet.file"
                                style="font-size: 25px;color: #000;"
                            >
                                <i class="fas fa-print"></i>
                            </a>&nbsp;&nbsp;&nbsp;
                            <a
                                :href="'{{\Config::get('filesystems.aws_url')}}' + sheet.file"
                                style="font-size: 25px;color: #000;"
                            >
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10 offset-sm-2 col-md-10 offset-md-2 col-lg-2 offset-lg-10">
                    <button
                        v-if="sheets_next_page_url"
                        role="button"
                        class="btn btn-default more"
                        @click="loadMoreSheets"
                    >
                        More
                    </button>
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
</index-sheet-music-component>
@endsection