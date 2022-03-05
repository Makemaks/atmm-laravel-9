@extends('layouts.layout-full-screen')
@section('title', 'Sheet Music')

@section('style')
  <link rel="stylesheet" type="text/css" href="/css/media-page.css">
  <style>

    .sheet {
      margin-bottom:30px;
    }

    .sheet .card {
      background-color: #d6d6d6;
      height:185px;
    }

    .sheet .link-containers {
      padding-top:5px;
    }

    .sheet .link-containers a {
      font-size: 18px;
      color: #000;
    }

    .search-container {
      margin-bottom:15px;
    }

    .slick-arrow {
        top: 28%;
        background-color: #eee;
        padding: 130px 7px;
        margin-top: -130px;
        border-radius: 100px;
        height: 395px;
        margin-right: 0 !important;
        margin-left: 0 !important;
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

    }

    @media (max-width: 991.98px) {
      .sheet .card {
        height:135px;
      }
    }

    @media only screen and (max-width : 768px) {

    }

    @media only screen and (max-width : 575px) {

    }
  </style>
@endsection
@section('content')
<index-sheet-music-component inline-template>
<div class="container">
    <div class="banner">
        <div class="row">
            <div class="col-md-6">
                <span class="hero_headline">
                    Sheet Music
                </span>
                <span class="hero_headline_content">
                    Sheet music is available for you to print, download, and
                    enjoy. I hope you have as much fun learning these songs
                    as I had writing them!
                </span>
            </div>
            <div class="col-md-6 d-xl-block d-lg-block d-md-block d-none position-relative">
                <img src="/img/sheet-music.png" class="img-fluid banner-image icon">
            </div>
        </div>
        @include('media-links')
        <div class="row">
            <div class="col-12">
                <div class="search-container">
                    <form
                        class="form-inline my-2 my-lg-0"
                        @submit.prevent="doSearchSheets"
                    >
                        @csrf
                        <input class="form-control search" type="hidden" name="s_fields" value="title" placeholder="Search for a video" aria-label="Search">
                        <div class="form-group has-search">
                            <input
                                class="form-control search"
                                type="text"
                                name="search"
                                id="search_videos"
                                placeholder="Search for a sheet"
                                aria-label="Search"
                                v-model="searchSheets"
                            />
                            <span class="fa fa-search form-control-feedback"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content-padding">
        <div class="row carousel">
            <div
                v-for="(sheet, index) in sheets"
                :key="index"
                class="col-md-4 sheet"
            >
                <div class="image-container" style="cursor: pointer;" @click="showSheetMusicModal(sheet.title, '{{\Config::get('filesystems.aws_url')}}' + sheet.file)">
                    <img class="img-fluid d-block mx-auto" :src="'{{\Config::get('filesystems.aws_url')}}' + sheet.image" :alt="sheet.title" style="max-height: 190px;">
                </div>
                <div class="text-center text-md-right link-containers">
                  <a
                    target="pdf-frame"
                    :href="'{{\Config::get('filesystems.aws_url')}}' + sheet.file"
                  >
                      <i class="fas fa-print"></i>
                  </a>&nbsp;&nbsp;&nbsp;
                  <a
                      :href="'{{\Config::get('filesystems.aws_url')}}' + sheet.file"
                  >
                      <i class="fas fa-download"></i>
                  </a>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-12 text-center text-md-right">
                <button
                    v-if="sheets_next_page_url"
                    role="button"
                    class="btn btn-default more"
                    @click="loadMoreSheets"
                >
                    More
                </button>
            </div>
        </div> --}}
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
                {{-- <i class="fas fa-angle-left pointer" style="position: "></i>
                <i class="fas fa-angle-right pointer"></i> --}}
            </div>
            </div>
        </div>
    </div>
</div>
</index-sheet-music-component>
@endsection
