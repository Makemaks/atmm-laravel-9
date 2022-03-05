@extends('layouts.layout-full-screen')
@section('title', 'Music')

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

    .song-image {
        width: 60px !important;
        padding: 0 !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        vertical-align: middle !important;
        height:45px !important;
    }

    .card.card_style {
      margin-bottom:20px;
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

    .slick-arrow {
        top: 35%;
        background-color: #eee;
        padding: 130px 7px;
        margin-top: -130px;
        border-radius: 100px;
        height: 350px;
    }

    .slick-arrow:hover {
        background-color: #aaa;
        color:#fff !important;
    }
    .slick-prev {
        left: -50px;
        margin-left: 40px;
        z-index: 10;
    }

    .slick-next {
        right: -50px;
        margin-right: 40px;
    }


    @media (max-width: 1199.98px) {
        .slick-prev {
            left: -40px;
        }

        .slick-next {
            right: -40px;
        }
    }

    @media (max-width: 991.98px) {

        .slick-arrow {
            top: 25%;
        }

        .content-padding table.selector {
            width:40%;
        }

        .slick-prev {
            left: -30px;
        }

        .slick-next {
            right: -30px;
        }
    }

    @media only screen and (max-width : 768px) {
        .content-padding table.selector {
            width:70%;
            margin-bottom:20px;
        }

        .slick-arrow {
            top: 15%;
        }

        .slick-prev {
            left: -25px;
        }

        .slick-next {
            right: -25px;
        }
    }

    @media only screen and (max-width : 575px) {

    }
  </style>

@endsection
@section('content')

<index-song-component inline-template>
<div class="container">
    <div class="banner">
        <div class="row">
            <div class="col-md-6">
                <span class="hero_headline">
                    Music
                </span>
                <span class="hero_headline_content">
                    Here you have access to my complete discography.
                    That's 50+ albums to stream and enjoy!<br><br>
                </span>
            </div>
            <div class="col-md-6 d-xl-block d-lg-block d-md-block d-none position-relative mt-5">
                <img src="/img/mclean.png" class="img-fluid banner-image">
            </div>
        </div>
        @include('media-links')
    </div>
    <div class="content-padding long">
        <div class="row" style="padding-bottom: 30px;">
            <div class="col-md-12">
                <div class="row">
                    <div
                        class="col-lg-4 col-md-4 col-sm-12 offset-lg-4 offset-md-4 offset-sm-0 offset-xs-0"
                        style="text-align: center;"
                    >
                        <table class="selector">
                            <tr>
                                <td
                                    :class="isSongsActive ? 'unactive' : 'active'"
                                    @click="isSongsActive = !isSongsActive; setCarouselSlide(); getAlbumList();"
                                    style="cursor: pointer;"
                                >
                                    Albums
                                </td>
                                <td
                                    :class="isSongsActive ? 'active' : 'unactive'"
                                    @click="isSongsActive = !isSongsActive; getSongsViaTab();"
                                    style="cursor: pointer;"
                                >
                                    Songs
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">



                      <div class="search-container">
                        <form
                            v-if="isSongsActive"
                            class="form-inline my-2 my-lg-0"
                            @submit.prevent="doSearchSongs"
                        >
                            @csrf
                            <input class="form-control search" id="search" type="hidden" name="s_fields" value="title" placeholder="Search" aria-label="Search"/>
                            <div class="form-group has-search">
                              <input
                                  class="form-control search"
                                  type="text"
                                  name="search"
                                  v-model="searchSongs"
                                  placeholder="Search"
                                  aria-label="Search"
                                  style="width: 100%;"
                              />
                              <span class="fa fa-search form-control-feedback"></span>
                            </div>
                        </form>
                        <form
                            v-else
                            class="form-inline my-2 my-lg-0"
                            @submit.prevent="doSearchAlbums"
                        >
                            @csrf
                            <input class="form-control search" type="hidden" name="s_fields" value="title" placeholder="Search" aria-label="Search"/>
                            <div class="form-group has-search">
                              <input
                                  class="form-control search"
                                  type="text"
                                  name="search"
                                  v-model="searchAlbums"
                                  placeholder="Search"
                                  aria-label="Search"
                                  style="width: 100%;"
                              />
                              <span class="fa fa-search form-control-feedback"></span>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isSongsActive">
            <div class="row">
                <div class="col-md-12">
                    <div class="float-left">
                        <h4>SONGS</h4>
                    </div>
                    <div class="float-right">
                        <div
                            class="form-group"
                        >
                            <div class="col-sm-12">
                                <select
                                    class="form-control1"
                                    style=""
                                    v-model="sortSongs"
                                >
                                    <option value="1">Sort by Oldest</option>
                                    <option value="2">Sort by Newest</option>
                                    <option selected value="3">Sort by Name</option>
                                </select>

                                <button style="display:none;" class="btn custom-primary" @click="shuffleSongs()">SHUFFLE <i class="fas fa-random"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-lg-12"
                   onmouseleave="$('#songlist_aplayer .musicsong_popover').popover('hide');"
               >
                <div id="songlist_aplayer"></div>
              </div>
            </div>

            @php /*
            <div class="row">
                <div class="col-lg-12">
                    <button
                        v-if="songs_next_page_url"
                        type="button"
                        role="button"
                        class="btn btn-default more"
                        @click="loadMoreSongs"
                        id="btnLoadMoreSongs"
                    > More </button>
                </div>
            </div>
            */ @endphp

        </div>
        <div v-else>
            <div id="isSearchNull" v-if="!selectedAlbum">
                <div class="row">
                    <div class="col-md-12">
                        <div class="float-left">
                            <h4>ALBUMS</h4>
                        </div>
                        <div class="float-right">
                            <div
                                class="form-group"
                            >
                                <div class="col-sm-8">
                                    <select
                                        class="form-control"
                                        style="min-width: 200px;"
                                        v-model="sortAlbums"
                                    >
                                        <option selected value="">SORT BY:</option>
                                        <option value="1">Oldest</option>
                                        <option value="2">Newest</option>
                                        <option value="3">By Name</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row carousel" v-if="albums.length > 0">
                    <div
                        v-for="(album, index) in albums"
                        :key="index"
                        class="col-lg-3 col-md-4 text-center"
                    >
                        <div
                            style="text-decoration:none;"
                        >
                            <div
                                class="card card_style"
                                style="height:165px; overflow: hidden;border:none; cursor: pointer;"
                                @click="selectAlbum(album)"
                            >
                                <img
                                    class="img-fluid"
                                    :src="'{{\Config::get('filesystems.aws_url')}}' + album.image"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="card-body text-center">
                        <b>No Album Available</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button
                            v-if="albums_next_page_url"
                            type="button"
                            role="button"
                            class="btn btn-default more"
                            @click="loadMoreAlbums"
                        > More </button>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="row" v-if="selectedAlbum">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <button
                                    type="button"
                                    role="button"
                                    class="btn"
                                    @click="getAlbumList()"
                                    style="background-color: transparent; color: black;"
                                >
                                    <i class="fas fa-arrow-circle-left"></i>
                                    BACK TO ALBUMS
                                </button>
                                <br />

                                @php /*
                                <div
                                    class="card card_style"
                                    style="height:165px; overflow: hidden;border:none; cursor: pointer;"
                                >
                                    <img
                                        class="img-fluid"
                                        :src="'{{\Config::get('filesystems.aws_url')}}' + selectedAlbum.image"
                                    />
                                </div>
                                */ @endphp

                            </div>
                            @php /*
                            <div
                                class="col-lg-9 col-md-9 col-sm-6 col-xs-12"
                                style="padding-top: 35px;"
                            >
                                <h3>@{{ selectedAlbum.album }}</h3>
                                <p>@{{ selectedAlbum.description }}</p>
                            </div>
                            */ @endphp
                        </div>

                        <div class="row">

                          <div class="col-lg-12"
                               onmouseleave="$('#album_aplayer .musicsong_popover').popover('hide');"
                           >
                              <div id="album_aplayer"></div>
                          </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <input id="current_musicsongid_n" type="hidden">
    <input id="currentaplayer_selected" type="hidden" value="album_aplayer">
</div>
</index-song-component>
@endsection
