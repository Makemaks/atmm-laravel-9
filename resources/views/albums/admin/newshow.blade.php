@extends('layouts.layout-admin')
@section('page_heading', $band_album->album. ' Songs')
@section('style')
    <style>
        .song-image {
            width: 60px !important;
            padding: 0 !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            vertical-align: middle !important;
            height:45px !important;
        }
    </style>
@endsection
@section('section')

<admin-newshow-album-component songs='{!! str_replace("'", "&apos;", $all_musics->toJson()) !!}' inline-template>
    <div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-6">
                    @include('fragments.search-list-form', ['default_field' => 'title'])
                </div>
                <div class="col-md-6 text-right">
                    <a type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#addMusicModal"
                        @click="showAddMusicModal({{$band_album->id}})"
                        data-backdrop="static"
                    >
                        <i class="fa fa-plus"></i>&nbsp; Add Music
                    </a>
                </div>
            </div>
            <div
                class="row panel panel-default"
                style="margin-top: 20px;"
            >
                <div class="panel-body" style=" padding: 0px;">
                    <table
                        class="table table-hover custom-table-css"
                    >
                        <thead>
                            <th>
                                <a href="{{
                                    generateQueryUrl('title')
                                }}">
                                    Title
                                    @if(!Request::has('sortBy') || Request::input('sortBy') == 'title')
                                        <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Audio</th>
                            <th>
                                <a href="{{
                                    generateQueryUrl('track_sequence')
                                }}">
                                    Track Sequence
                                    @if(!Request::has('sortBy') || Request::input('sortBy') == 'track_sequence')
                                        <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($musics as $music)
                                <tr>
                                    <td>{{$music->title}}</td>
                                    <td>
                                        <a href="{{\Config::get('filesystems.aws_url').$music->audio}}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            {{$music->audio}}
                                        </a>
                                    </td>
                                    <td >
                                        <span v-if="initial_load_album_music">
                                            <span @click="selectTrackSequence({{$music->pivot->album_music_id}},{{$music->id}},{{$band_album->id}},{{$music->pivot->track_sequence}})" style="cursor: pointer;">    <span id="trackseq_{{$music->pivot->album_music_id}}">
                                                    {{$music->pivot->track_sequence}}
                                                </span>
                                            </span>
                                        </span>
                                        <span v-else>
                                            <span v-if="album_music_id == {{$music->pivot->album_music_id}}">
                                            <form class="form-horizontal was-validated" @submit.prevent="updateSongTrackSequenceNew" >

                                                <input type="number" size="8" v-model="track_sequence_for_update">
                                                <span class="control-label" v-if="track_sequence_for_update_error">
                                                    Track Sequence is required
                                                </span>
                                                <button type="submit" class="btn btn-success btn-xs" :disabled="submitting" >
                                                    <i class="fa fa-save"></i>
                                                </button>
                                                 <span @click="cancelTrackSequence()" class="btn btn-danger btn-xs"> <i class="fa fa-close"></i> </span>
                                            </form>
                                            </span>
                                            <span v-else>
                                                <span @click="selectTrackSequence({{$music->pivot->album_music_id}},{{$music->id}},{{$band_album->id}},{{$music->pivot->track_sequence}})" style="cursor: pointer;">
                                                    <span id="trackseq_{{$music->pivot->album_music_id}}">
                                                        {{$music->pivot->track_sequence}}
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        <a
                                            type="button"
                                            data-target="#changeTrackSequence"
                                            class="btn btn-success btn-circle"
                                            data-toggle="modal"
                                            data-backdrop="static"
                                            data-keyboard="false"
                                            @click="changeTrackSequence({{$music->id}}, {{$band_album->id}}, {{$music->pivot->track_sequence}})"
                                        >
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a
                                            type="button"
                                            data-target="#removeMusicFromBandAlbum"
                                            class="btn btn-danger btn-circle"
                                            data-toggle="modal"
                                            data-backdrop="static"
                                            data-keyboard="false"
                                            @click="showRemoveMusicModal({{$music->id}}, {{$band_album->id}})"
                                        >
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div style="float:right">
                    {{ $musics->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="addMusicModal" tabindex="-1"  role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form class="form-horizontal was-validated" @submit.prevent="addMultipleMusic" >
                        <div class="modal-header">
                            <h4 class="modal-title">Add Music</h4>
                                <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                @click="reset"
                                :disabled="submitting"
                            >&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-horizontal">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" v-model="search" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit" style="padding:9px 15px">
                                        <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <input type="button" value="Select All Music" class="btn btn-primary" @click="checkAll()">
                                <input type="button" value="Unselect All Music" class="btn btn-primary" @click="unCheckAll()">
                                <div style="overflow-y: auto;max-height:380px;">
                                    <table class="table table-hover custom-table-css">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Image</th>
                                                <th>
                                                    <a href="javascript:void(0)" @click="toggleSort()">
                                                        <i class="fa" :class="{ 'fa-chevron-up': sort === 'ASC', 'fa-chevron-down': sort === 'DESC' }"></i>
                                                        Song Title
                                                    </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="song in filteredSongs">
                                                <td style="width:40px;"><input name="musicsel" type="checkbox" :value="song.id" v-model="song.checked" :checked="song.checked"></td>
                                                <td class="song-image" :style="`background: url({{\Config::get('filesystems.aws_url')}}` + song.image + `);`"></td>
                                                <td>@{{ song.title }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="submitting"
                        >Add Music</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" id="changeTrackSequence" role="dialog">
            <div class="modal-dialog">
            <form
                class="form-horizontal was-validated"
                @submit.prevent="updateSongTrackSequence"
            >
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Change Track Sequence</h4>
                      <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        @click="reset"
                        :disabled="submitting"
                    >&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div
                                class="form-group"
                                :class="track_sequence_for_update_error ? 'has-error has-feedback' : ''"
                            >
                                <label for="inputState" class="col-sm-2 control-label">
                                    Track S.
                                </label>
                                <div class="col-sm-8">
                                    <input
                                        type="number"
                                        class="form-control"
                                        v-model="track_sequence_for_update"
                                    />
                                    <span
                                        class="control-label"
                                        v-if="track_sequence_for_update_error"
                                    >
                                        Track Sequence is required
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="submitting"
                    >Update</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>

        <div
            class="modal fade"
            id="removeMusicFromBandAlbum"
            tabindex="-1"
            role="dialog"
            aria-labelledby="removeMusicFromBandAlbum"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            Remove
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to remove this music ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form
                            @submit.prevent="removeMusic"
                        >
                        {{ csrf_field() }}
                            <button
                                type="submit"
                                class="btn btn-danger"
                                :disabled="submitting"
                            > Remove </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</admin-newshow-album-component>
@stop
