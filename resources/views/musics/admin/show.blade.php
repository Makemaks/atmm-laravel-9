@extends('layouts.layout-admin')
@section('page_heading', $music->title. ' Artists & Authors')
@section('section')
<admin-show-music-component inline-template>
    <div>
        <div class="col-sm-12">
            <div class="row">
                <div style="float: left; font-size: 18px; padding-top: 15px;">
                    Artists
                </div>
                <div style="float:right">
                    <a type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#addArtist"
                        data-backdrop="static"
                        @click="showAddArtist({{$music->id}})"
                    >
                        <i class="fa fa-plus"></i>&nbsp; Add Artist
                    </a>
                </div>
            </div>
            <div
                class="row panel panel-default"
                style="margin-top: 10px;"
            >
                <div class="panel-body" style=" padding: 0px;">
                    <table
                        class="table table-hover custom-table-css"
                    >
                        <thead>
                            <th>Name</th>
                            <th style="width: 200px;">Action</th>
                        </thead>
                        <tbody>
                            @foreach($artists as $artist)
                                <tr>
                                    <td>{{$artist->name}}</td>
                                    <td>
                                        <a
                                            type="button"
                                            data-target="#removeArtist"
                                            class="btn btn-danger btn-circle"
                                            data-toggle="modal"
                                            data-backdrop="static"
                                            data-keyboard="false"
                                            @click="showRemoveArtist({{$music->id}}, {{$artist->id}})"
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
        <div class="col-sm-12">
            <div class="row">
                <div style="float: left; font-size: 18px; padding-top: 15px;">
                    Authors
                </div>
                <div style="float:right">
                    <a type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#addAuthor"
                        data-backdrop="static"
                        @click="showAddArtist({{$music->id}})"
                    >
                        <i class="fa fa-plus"></i>&nbsp; Add Author
                    </a>
                </div>
            </div>
            <div
                class="row panel panel-default"
                style="margin-top: 10px;"
            >
                <div class="panel-body" style=" padding: 0px;">
                    <table
                        class="table table-hover custom-table-css"
                    >
                        <thead>
                            <th>Name</th>
                            <th style="width: 200px;">Action</th>
                        </thead>
                        <tbody>
                            @foreach($authors as $author)
                                <tr>
                                    <td>{{$author->name}}</td>
                                    <td>
                                        <a
                                            type="button"
                                            data-target="#removeAuthor"
                                            class="btn btn-danger btn-circle"
                                            data-toggle="modal"
                                            data-backdrop="static"
                                            data-keyboard="false"
                                            @click="showRemoveAuthor({{$music->id}}, {{$author->id}})"
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
        <div class="modal fade" id="addArtist" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <form
                class="form-horizontal was-validated"
                @submit.prevent="addArtist"
            >
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add Artist</h4>
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
                                :class="artist_id_error ? 'has-error has-feedback' : ''"
                            >
                                <label for="inputState" class="col-sm-2 control-label">Artist</label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="artist_id">
                                        <option value="">----- Choose Artist -----</option>
                                        @foreach($all_artists as $artist)
                                            <option value="{{$artist->id}}">
                                                {{$artist->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="control-label" v-if="artist_id_error">Artist is required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="submitting"
                    >Add Artist</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="addAuthor" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <form
                class="form-horizontal was-validated"
                @submit.prevent="addAuthor"
            >
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add Author</h4>
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
                                :class="author_id_error ? 'has-error has-feedback' : ''"
                            >
                                <label for="inputState" class="col-sm-2 control-label">
                                    Author
                                </label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="author_id">
                                        <option value="">----- Choose Author -----</option>
                                        @foreach($all_authors as $author)
                                            <option value="{{$author->id}}">
                                                {{$author->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="control-label" v-if="author_id_error">
                                        Author is required
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
                    >Add Author</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
        <div
            class="modal fade"
            id="removeArtist"
            tabindex="-1"
            role="dialog"
            aria-labelledby="removeArtist"
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
                        Are you sure you want to remove this artist ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form
                            @submit.prevent="removeArtist"
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
        <div
            class="modal fade"
            id="removeAuthor"
            tabindex="-1"
            role="dialog"
            aria-labelledby="removeAuthor"
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
                        Are you sure you want to remove this author ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form
                            @submit.prevent="removeAuthor"
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
</admin-show-music-component>
@stop
