@extends('layouts.layout')

@section('style')
<style>
    .hero_headline {
        display: block;
        margin-bottom:20px;
    }

    #search_videos {
        width: 100%;
    }
    .user-videos-bg-image {
        background-position: 20px !important;
    }

    .w-padding {
        padding: 50px 40px;
    }

    .search-container {
        padding-top: 20px;
    }

    .socials {
        padding: 40px 103px;
    }

    #search_videos {
        margin-bottom: 20px;
    }

    .link-container {
        padding-top: 65px;
        padding-bottom: 20px;
    }

    .link-container a {
        font-weight: normal;
    }
</style>
@endsection

@section('content')
<index-song-component inline-template>
    <div style="overflow: hidden;">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 songwriter_sunday_school">
                <img src="{{ asset('img/songwriterlogo-2.png') }}" class="image-logo" />
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6" style="padding: 40px 103px;background-color: #dbdbdb;">
                <div class="float-right">
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
                <div class="row" style="padding-right: 40px; padding-left: 40px;">
                    <div
                        class="col-lg-12 col-md-12 col-sm-12"
                    >
                        <span class="hero_headline">
                            <small>Featured Album</small>
                        </span>
                    </div>
                </div>
                <div style="padding-right: 40px; padding-left: 40px;">
                    <table style="width: 100%">
                        <tr>
                            <td>
                                <a
                                    href="#"
                                    role="button"
                                    class="btn btn-block active"
                                >Play Album</a>
                            </td>
                            <td>
                                <a
                                    href="#"
                                    role="button"
                                    class="btn btn-block btn-outline-primary"
                                >Shuffle Catalog</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row w-padding">
                <div
                    class="col-6 offset-3"
                >
                    <span class="hero_headline">
                        Music
                    </span>
                    <span class="hero_headline_content">
                            Here you have access to Michael McLean’s complete discography - that’s 50+ albums to stream and enjoy!
                            <br><br>You can explore the music by albums or song title. Albums you can sort by name, oldest, and newest, and songs you find with the search bar! Or you click “Shuffle Catalog” and let the Spirit guide your playlist.
                    </span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="padding-top: 40px;padding-bottom: 65px;">
                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-2 offset-md-2 offset-xs-1">
                    <div class="row">
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12">
                            <a
                                role="button"
                                id="videos"
                                href="/videos"
                                style="font-weight: normal;"
                                class="btn btn-block btn_round btn_default"
                            >Videos</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="musics"
                                href="/musics"
                                style="font-weight: normal;"
                                class="btn btn-block btn_round btn_default active"
                            >Music</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="sheet_musics"
                                href="/sheet_musics"
                                style="font-weight: normal;"
                                class="btn btn-block btn_round btn_default"
                            >Sheet Music</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="instrumentals"
                                href="/instrumentals"
                                style="font-weight: normal;"
                                class="btn btn-block btn_round btn_default"
                            >Sing Along</a>
                        </div>
                        <div class="col-lg-2 custom_col col-sm-12 col-md-12 ">
                            <a
                                role="button"
                                id="podcasts"
                                href="/podcasts"
                                style="font-weight: normal;"
                                class="btn btn-block btn_round btn_default"
                            >Podcasts</a>
                        </div>
                    </div>
                </div>
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
                                    :class="isSongsActive ? 'unactive' : 'active'"
                                    @click="isSongsActive = !isSongsActive"
                                    style="cursor: pointer;"
                                >
                                    Albums
                                </td>
                                <td
                                    :class="isSongsActive ? 'active' : 'unactive'"
                                    @click="isSongsActive = !isSongsActive"
                                    style="cursor: pointer;"
                                >
                                    Songs
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <form
                            v-if="isSongsActive"
                            class="form-inline my-2 my-lg-0"
                            @submit.prevent="doSearchSongs"
                        >
                            @csrf
                            <input class="form-control mr-sm-2 search" id="search" type="hidden" name="s_fields" value="title" placeholder="Search" aria-label="Search"/>
                            <input
                                class="form-control mr-sm-2 search"
                                type="search"
                                name="search"
                                v-model="searchSongs"
                                placeholder="Search"
                                aria-label="Search"
                                style="width: 100%;"
                            />
                        </form>
                        <form
                            v-else
                            class="form-inline my-2 my-lg-0"
                            @submit.prevent="doSearchAlbums"
                        >
                            @csrf
                            <input class="form-control mr-sm-2 search" type="hidden" name="s_fields" value="title" placeholder="Search" aria-label="Search"/>
                            <input
                                class="form-control mr-sm-2 search"
                                type="search"
                                name="search"
                                v-model="searchAlbums"
                                placeholder="Search"
                                aria-label="Search"
                                style="width: 100%;"
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isSongsActive">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="float-left">
                        <h4>SONGS</h4>
                    </div>
                    <div class="float-right">
                        <div
                            class="form-group"
                        >
                            <div class="col-sm-8">
                                <select
                                    class="form-control"
                                    style="min-width: 200px;"
                                    v-model="sortSongs"
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
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <table
                        class="table table-striped"
                        style="border: 1px solid lightgray;"
                    >
                        <tbody v-if="(songs).length > 0">
                            <tr v-for="(song, index) in songs" :key="index">
                                <td style="background-color: #d6d6d6; width: 7%;"></td>
                                <td>@{{ song.title }}</td>
                                <td>
                                    <center>
                                    <audio
                                        :id="'song' + song.id"
                                        :data-id="song.id"
                                        :src="'{{\Config::get('filesystems.aws_url')}}' + song.audio"
                                        :ref="'song' + song.id"
                                        style="height:30px"
                                    >
                                    </audio>
                                    </center>
                                </td>
                                <td>
                                    <div class="float-right" style="color: #b8e986;">
                                        <button
                                            @click="playSong(song.id)"
                                            class="btn"
                                            style="background-color: transparent;"
                                        >
                                            <i
                                                v-if="play_id !== song.id || !isPlaying"
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
                        <tbody
                            class="card"
                            style="background-color: #d6d6d6"
                            v-else
                        >
                            <tr>
                                <td>
                                    <div class="card-body text-center">
                                        <b>No Song Available</b>
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
                        v-if="songs_next_page_url"
                        type="button"
                        role="button"
                        class="btn btn-default more"
                        @click="loadMoreSongs"
                    > More </button>
                </div>
            </div>
        </div>
        <div v-else>
            <div id="isSearchNull" v-if="!selectedAlbum">
                <div class="row">
                    <div class="col-md-10 offset-md-1 albums">
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
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="row" v-if="albums.length > 0">
                            <div
                                v-for="(album, index) in albums"
                                :key="index"
                                class="col-lg-3 custom_col col-sm-12 col-md-12 text-center"
                            >
                                <div
                                    style="text-decoration:none;"
                                >
                                    <div
                                        class="card card_style"
                                        style="height:165px; overflow: hidden;border: 1px solid #d6d6d6; cursor: pointer;"
                                        @click="selectAlbum(album)"
                                    >
                                        <img
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 offset-sm-2 col-md-10 offset-md-2 col-lg-2 offset-lg-10">
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
                    <div class="col-md-10 offset-md-1 albums">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <button
                                    type="button"
                                    role="button"
                                    class="btn"
                                    @click="selectedAlbum = ''"
                                    style="background-color: transparent; color: black;"
                                >
                                    <i class="fas fa-arrow-circle-left"></i>
                                    BACK TO ALBUMS
                                </button>
                                <br />
                                <div
                                    class="card card_style"
                                    style="height:165px; overflow: hidden;border: 1px solid #d6d6d6;"
                                >
                                    <img :src="'{{\Config::get('filesystems.aws_url')}}' + selectedAlbum.image" />
                                </div>
                            </div>
                            <div
                                class="col-lg-9 col-md-9 col-sm-6 col-xs-12"
                                style="padding-top: 35px;"
                            >
                                <h3>@{{ selectedAlbum.album }}</h3>
                                <p>@{{ selectedAlbum.description }}</p>
                            </div>
                        </div>
                        <div class="row" style="padding-top:30px;">
                            <div class="col-md-12">
                                <table
                                    class="table table-striped"
                                    style="border: 1px solid lightgray;"
                                >
                                    <tbody v-if="albumSongs.length > 0">
                                        <tr v-for="(song, index) in albumSongs" :key="index">
                                            <td style="background-color: #d6d6d6; width: 7%;"></td>
                                            <td>@{{ song.title }}</td>
                                            <td>
                                                <center>
                                                <audio
                                                    :id="'albumSong' + song.id"
                                                    :data-id="song.id"
                                                    :src="'{{\Config::get('filesystems.aws_url')}}' + song.audio"
                                                    :ref="'song' + song.id"
                                                    style="height:30px"
                                                >
                                                </audio>
                                                </center>
                                            </td>
                                            <td>
                                                <div class="float-right" style="color: #b8e986;">
                                                    <button
                                                        @click="playAlbumSong(song.id)"
                                                        class="btn"
                                                        style="background-color: transparent;"
                                                    >
                                                        <i
                                                            v-if="album_play_id !== song.id || !isAlbumSongPlaying"
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
                                    <tbody
                                        class="card"
                                        style="background-color: #d6d6d6"
                                        v-else
                                    >
                                        <tr>
                                            <td>
                                                <div class="card-body text-center">
                                                    <b>No Song Available</b>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style = "padding-top: 15%">
            <div class="col-sm-12 col-md-12">
            <div class="footer"> FOOTER </div>
            </div>
        </div>
    </div>
</index-song-component>
@endsection