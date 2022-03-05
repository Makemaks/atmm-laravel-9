@extends('layouts.layout-full-screen')
@section('title', 'Podcasts')

@section('style')
  <link rel="stylesheet" type="text/css" href="/css/media-page.css">
  <style>

    .aplayer .aplayer-info .aplayer-controller .aplayer-bar-wrap .aplayer-bar .aplayer-played .aplayer-thumb {
      transform: scale(1.2) !important;
      background: #000000 !important;
    }

    .aplayer-played {
      background: #000000 !important;
    }


    .sheet {

    }

    .sheet .card {
        background-color: #d6d6d6;
        height:165px;
    }

    .content-padding table.selector {
        width:80%;
        margin:0 auto;
        text-align: center;
    }

    .content-padding table.selector tr td {
        width: 50%;
    }

    .search-container {
        margin-bottom:15px;
    }

    .has-search .form-control {
        width:100%;
    }

    .hero_headline_content {
        height: 84px;
    }

    @media (max-width: 1199.98px) {

    }

    @media (max-width: 991.98px) {
        .content-padding table.selector {
            width:40%;
        }

        .hero_headline_content {
            height: 67px;
        }
    }

    @media only screen and (max-width : 768px) {
        .content-padding table.selector {
            width:70%;
            margin-bottom:20px;
        }

        .hero_headline_content {
            height: initial;
        }
    }

    @media only screen and (max-width : 575px) {

    }
  </style>
@endsection
@section('content')
<index-podcast-component inline-template>
<div class="container">
    <div class="banner">
        <div class="row">
            <div class="col-md-6">
                <span class="hero_headline">
                    Podcasts
                </span>
                <span class="hero_headline_content">
                    Listen to my podcast here or click the links below to
                    enjoy and subscribe on your favorite podcast platform.
					@php /*
				   <div class="row">
                        <div class="col-3">
                            <a href="https://podcasts.apple.com/us/podcast/songwriter-sunday-school-with-michael-mclean/id1485647311" target="_blank">
                                Apple
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="https://www.stitcher.com/podcast/songwriter-sunday-school-with-michael-mclean?refid=stpr" target="_blank">Stitcher</a>
                        </div>
                        <div class="col-3">
                            <a href="https://open.spotify.com/show/7A5EPVBFGpyIExAzfH88RZ?si=n78yIrQNTZ29RSE5urjQLA" target="_blank">Spotify</a>
                        </div>
                    </div>
					*/
					@endphp
                </span>
            </div>
            <div class="col-md-6 d-xl-block d-lg-block d-md-block d-none position-relative">
                <img src="/img/podcasts.png" class="img-fluid banner-image icon">
                @php /*
                <img :src="isPodcastsActive ? '/img/podcasts.png' : '/img/audiobook.jpg'" class="img-fluid banner-image icon">
                */ @endphp
            </div>
        </div>
        @include('media-links')
    </div>
    <div class="content-padding long">
      <div class="row mb-4">
          <div class="col-lg-4 col-md-4 col-sm-12 offset-lg-4 offset-md-4 offset-sm-0 offset-xs-0"
                style="text-align: center;"
            >
              <table class="selector">
                  <tr>
                      <td
                          :class="isPodcastsActive ? 'active' : 'unactive'"
                          @click="isPodcastsActive = !isPodcastsActive; getPodcasts('podcast');"
                          style="cursor: pointer;"
                      >
                          Podcasts
                      </td>
                      <td
                          :class="!isPodcastsActive ? 'active' : 'unactive'"
                          @click="isPodcastsActive = !isPodcastsActive;  getPodcasts('audiobook');"
                          style="cursor: pointer;"
                      >
                          Audiobooks
                      </td>
                  </tr>
              </table>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="search-container">
                    <form
                        class="form-inline my-2 my-lg-0"
                        @submit.prevent="doSearchPodcast"
                    >
                        @csrf
                        <input class="form-control search" type="hidden" name="s_fields" value="title" aria-label="Search">
                        <div class="form-group has-search" v-if="isPodcastsActive">
                            <input
                                class="form-control search"
                                type="text"
                                name="search"
                                placeholder="Search"
                                aria-label="Search"
                                v-model="search_podcast"
                            />
                            <span class="fa fa-search form-control-feedback"></span>
                        </div>
                        <select v-model="sort_podcast" class="form-control" @change="doSearchPodcast" style="margin: 20px 0 0 auto" v-if="isPodcastsActive">
                            <option value="newest">Newest</option>
                            <option value="title">Title</option>
                            <option value="oldest">Oldest</option>
                        </select>
                        <div class="form-group has-search" v-if="!isPodcastsActive">
                            <input
                                class="form-control search"
                                type="text"
                                name="search"
                                placeholder="Search"
                                aria-label="Search"
                                v-model="search_audiobook"
                            />
                            <span class="fa fa-search form-control-feedback"></span>
                        </div>
                        <select v-model="sort_audiobook" class="form-control" @change="doSearchPodcast" style="margin: 20px 0 0 auto" v-if="!isPodcastsActive">
                            <option value="newest">Newest</option>
                            <option value="title">Title</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </form>
                </div>
            </div>
      </div>



        <div class="row">
          <div class="col-lg-12">
            <div id="podcast_aplayer"></div>
          </div>
        </div>

        <div class="col-lg-12"
             onmouseleave="$('#podcast_aplayer .musicsong_popover').popover('hide');"
         >
          <div id="podcast_aplayer"></div>
        </div>

      @php /*
       <div v-show="isPodcastsActive">
         <div class="row">
           <div class="col-lg-12">
             <div id="podcast_aplayer"></div>
           </div>
         </div>
       </div>
       <div v-show="!isPodcastsActive">
         <div class="row">
           <div class="col-lg-12">
             <div id="audiobook_aplayer"></div>
           </div>
         </div>
       </div>
      */ @endphp


    </div>

    <input id="current_musicsongid_n" type="hidden">
    <input id="currentaplayer_selected" type="hidden" value="podcast_aplayer">
</div>
</index-podcast-component>
@endsection
