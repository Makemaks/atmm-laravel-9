@extends('layouts.layout-admin')
@section('page_heading', $band_album->album. ' Songs')
@section('section')
<admin-show-album-component inline-template>
    <div>
        <div class="col-sm-12">
            <div class="row">
                <div style="float:right">
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
                            <th>Title</th>
                            <th>Audio</th>
                            <th>Track Sequence</th>
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
                                    <td>{{$music->pivot->track_sequence}}</td>
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
        </div>
        <div class="modal fade" id="addMusicModal" role="dialog">
            <div class="modal-dialog">
            <form
                class="form-horizontal was-validated"
                @submit.prevent="addMusic"
            >
                  <div class="modal-content">
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
                            <div
                                class="form-group"
                                :class="music_id_error ? 'has-error has-feedback' : ''"
                            >
                                <label for="inputState" class="col-sm-2 control-label">Music</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="music_id">
                                        <option value="">----- Choose Music -----</option>
                                        @foreach($all_musics as $music)
                                            <option value="{{$music->id}}">{{$music->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="control-label" v-if="music_id_error">Music is required</span>
                                </div>
                            </div>
                            <div
                                class="form-group"
                                :class="track_sequence_error ? 'has-error has-feedback' : ''"
                            >
                                <label for="inputState" class="col-sm-2 control-label">
                                    Track S.
                                </label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" v-model="track_sequence"/>
                                    <span class="control-label" v-if="track_sequence_error">
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
                    >Add Music</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="changeTrackSequence" role="dialog">
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
</admin-show-album-component>
@stop
