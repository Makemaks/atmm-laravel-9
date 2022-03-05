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
<index-podcast-component inline-template>
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
                        Podcasts
                    </span>
                    <span class="hero_headline_content">
                        The All Things Michael Mclean podcasts are available here and on your favorite podcast-streaming platforms:<br><br>
                        Spotify<br>
                        iTunes<br>
                        Etc.
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
                                class="btn btn-block btn_round btn_default"
                            >Sing Along</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="podcasts"
                                href="/podcasts"
                                class="btn btn-block btn_round btn_default active"
                            >Podcasts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="padding-bottom: 10px; padding-top: 20px;">
            <div class="col-lg-3 col-md-12 col-sm-12 offset-lg-9">
            <form
                class="form-inline my-2 my-lg-0"
                @submit.prevent="doSearchPodcast"
            >
                <input
                    class="form-control mr-sm-2 search"
                    type="search"
                    placeholder="Search"
                    aria-label="Search"
                    v-model="search"
                />
            </form>
            </div>
        </div>
        <div class="row" style="padding-bottom: 30px;">
             <div class="col-md-10 offset-md-1">
                <div class="row">
                    <div
                        class="col-lg-4 col-md-4 col-sm-12 offset-lg-4 offset-md-4 offset-sm-0 offset-xs-0"
                        style="text-align: center;"
                    >
                        <table style="width: 80%;">
                            <tr>
                                <td
                                    :class="isPodcastsActive ? 'active' : 'unactive'"
                                    @click="isPodcastsActive = !isPodcastsActive"
                                    style="cursor: pointer;"
                                >
                                    Podcasts
                                </td>
                                <td
                                    :class="!isPodcastsActive ? 'active' : 'unactive'"
                                    @click="isPodcastsActive = !isPodcastsActive"
                                    style="cursor: pointer;"
                                >
                                    Audiobooks
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isPodcastsActive">
            <div
                class="row"
                style="padding-top: 20px;"
            >
                <div class="col-lg-10 offset-lg-1">
                    <table
                        class="table table-striped"
                        style="border: 1px solid lightgray;"
                    >
                        <tbody>
                            <tr
                                v-for="(podcast, index) in podcasts"
                                :key="index"
                            >
                                <td style="background-color: #d6d6d6; width: 7%;"></td>
                                <td>
                                    @{{ podcast.title }} /
                                    @{{ podcast.date }}
                                </td>
                                <td>
                                    <center>
                                        <audio
                                            :id="'podcast' + podcast.id"
                                            :data-id="podcast.id"
                                            :src="'{{\Config::get('filesystems.aws_url')}}' + podcast.audio"
                                            style="height:30px"
                                        >
                                        </audio>
                                    </center>
                                </td>
                                <td>
                                    <div class="float-right" style="color: #b8e986;">
                                        <button
                                            class="btn btn-play-pause"
                                            id="1"
                                            style="background-color: transparent;"
                                            @click="playPodcast(podcast.id)"
                                        >
                                            <i
                                                v-if="play_id !== podcast.id || !isPlaying"
                                                class="fas fa-play"
                                                style="color: #c3e691;"
                                            ></i>
                                            <i
                                                v-else
                                                class="fas fa-pause"
                                                style="color: #c3e691;"
                                            ></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10 offset-sm-2 col-md-10 offset-md-2 col-lg-2 offset-lg-10">
                    <button
                        role="button"
                        v-if="podcasts_next_page_url"
                        class="btn btn-default more"
                        @click="loadMorePodcasts"
                    > MORE </button>
                </div>
            </div>
        </div>
        <div class="row" v-else>
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
</index-podcast-component>
@endsection