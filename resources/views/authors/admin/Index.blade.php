@extends('layouts.layout-admin')
@section('page_heading','Authors')
@section('section')
<admin-index-author-component inline-template>
    <div class="col-sm-12">
        <div class="modal fade" id="deleteArtist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-trash"></i> Delete
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form ref="delete_form" action="" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"> Delete </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <searched :search="search" @gosearch="getAuthorList('search')"></searched>
            </div>
            <div class="col-md-6 text-right">
                <a type="button" href="/authors/create" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
                <table class="table table-hover custom-table-css">
                    <thead>
                        <th>
                          <a href="#" @click="sortAuthorList('name')">
                              NAME
                              <span v-if="sort_field == 'name'">
                                <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                              </span>
                              <span v-if="sort_field != 'name'"> <i class="fas fa-chevron-down"></i> </span>
                          </a>
                        </th>
                        <th>SONGS</th>
                        <th style="width: 12%;">ACTIONS</th>
                    </thead>

                    <tbody v-if="allAuthor.length > 0">
                        <tr v-for="author,key in allAuthor">
                            <td>@{{author.name}}</td>
                            <td>
                                <i  class="fa fa-music text-info"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    @click="getSongList(author.id,'Add')"
                                    style="cursor: pointer;"
                                    title="Add Songs"
                                >+</i>
                                <i  class="fa fa-music text-info"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    @click="getSongList(author.id,'Remove')"
                                    style="cursor: pointer;margin-left: 15px;"
                                    title="Remove Multiple Songs From This Author"
                                > - </i>
                                <ul style="padding:0" v-if="author.music_details.length > 0">
                                  <li v-for="song,ind in author.music_details" style="list-style: none;">
                                    <i  class="fa fa-trash text-danger"
                                        data-keyboard="false"
                                        data-backdrop='static'
                                        style="cursor: pointer;"
                                        title="Remove this song"
                                        @click="showDeleteSongForAuthor(author.id, author.name, song.id, song.title)"
                                    >
                                    </i>
                                    @{{song.title}}
                                  </li>
                                </ul>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    @click="getSongList(author.id)"
                                    style="cursor: pointer;"
                                    title="Add Songs"
                                    class="btn btn-primary btn-circle"
                                >
                                    <i  class="fa fa-music"></i>
                                </button>

                                <a :href="'/authors/' + author.id + '/edit'" type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>

                                <button
                                    type="button"
                                    id="delete_details"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#deleteArtist"
                                    @click="deleteAuthor(author.id)"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <loading :active.sync="isLoadingAuthorList" :can-cancel="false" :is-full-page="false"></loading>
        </div>
        <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getAuthorList()"></pagination>
            </div>
        </div>


        <!-- Modals -->
        <div class="modal" id="selectSongs" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <form class="form-horizontal was-validated" @submit.prevent="processMultipleSong" >
                    <div class="modal-header">
                        <h4 class="modal-title">@{{task}} Songs - @{{author_name}}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body vld-parent">
                        <loading :active.sync="isLoadingSongList" :can-cancel="false" :is-full-page="false"></loading>
                        <div class="input-group">
                            <input type="text" class="form-control" name="searchsong" v-model="searchsong" placeholder="Search">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="button" style="padding:9px 15px">
                                <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <input type="button" value="Select All Songs" class="btn btn-primary" @click="checkAll()">
                        <input type="button" value="Unselect All Songs" class="btn btn-primary" @click="unCheckAll()">
                        <div style="overflow-y: auto;max-height:380px;">
                            <table class="table table-hover custom-table-css" >
                                <thead>
                                    <th colspan="2">
                                        <a href="javascript:void(0)" @click="toggleSort()">
                                            <i class="fa" :class="{ 'fa-chevron-up': sort === 'ASC', 'fa-chevron-down': sort === 'DESC' }"></i>
                                            Song Title
                                        </a>
                                    </th>
                                </thead>
                                <tbody v-if="filteredSongs.length > 0">
                                    <tr v-for="song,key in filteredSongs">
                                        <td>
                                            <input name="songsel" type="checkbox" :value="song.id" v-model="song.checked" :checked="song.checked">
                                        </td>
                                        <td>@{{song.title}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="submitting"
                        >@{{task}} Songs</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div class="modal" id="deleteSongForAuthor" tabindex="-1" role="dialog" aria-labelledby="deleteSongForAuthor" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            Remove
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to remove the song <u>@{{song_title}}</u> from <u>@{{author_name}}</u>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form
                            @submit.prevent="removeSong"
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
                <loading :active.sync="isLoadingRemoveSong" :can-cancel="false" :is-full-page="true"></loading>
            </div>
        </div>

    </div>
</admin-index-author-component>
@stop
