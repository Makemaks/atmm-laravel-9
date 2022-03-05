@extends('layouts.layout-admin')
@section('page_heading','Albums')
@section('section')
<admin-index-album-component inline-template>
    <div class="col-sm-12">
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <form
                            ref="delete_form"
                            action=""
                            method="post"
                        >
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
                    <searched :search="search" @gosearch="getAlbumList('search')"></searched>
            </div>
            <div class="col-md-6 text-right">
                <a type="button" href="/albums/create" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div class="row panel panel-default" style="margin-top: 20px;" >
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingAlbumList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover custom-table-css" >
                    <thead>
                        <th>
                            ARTWORK
                        </th>
                        <th>LINER</th>
                        <th>
                            <a href="#" @click="sortAlbumList('album')">
                                ALBUM TITLE
                                <span v-if="sort_field == 'album'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'album'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th>SONGS</th>
                        <th>
                            <a href="#" @click="sortAlbumList('release_date')">
                                RELEASE DATE
                                <span v-if="sort_field == 'release_date'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'release_date'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th>
                            <a href="#" @click="sortAlbumList('is_public')">
                                PUBLIC
                                <span v-if="sort_field == 'is_public'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'release_date'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th width="12%">ACTION</th>
                    </thead>
                    <tbody v-if="allAlbum.length > 0">
                        <tr v-for="band_album,key in allAlbum">
                            <td style="height: 50px; width: 50px;">
                                <expandable-image
                                  class="image"
                                  :src="aws_url + band_album.image"
                                  :alt="band_album.album"
                                  title="band_album.album">
                                </expandable-image>
                            </td>
                            <td>
                              <span v-if="band_album.liner != ''">
                                <a :href="aws_url + band_album.liner" target="_blank" rel="noopener noreferrer">
                                  <i class="fas fa-download"></i>
                                </a>
                              </span>
                            </td>
                            <td>@{{ band_album.album }}</td>
                            <td>
                              <i  class="fa fa-music text-info"
                                  data-keyboard="false"
                                  data-backdrop='static'
                                  @click="getSongList(band_album.id,'Add')"
                                  style="cursor: pointer;"
                                  title="Add Multiple Songs For This Album"
                              >+</i>
                              <i  class="fa fa-music text-info"
                                  data-keyboard="false"
                                  data-backdrop='static'
                                  @click="getSongList(band_album.id,'Remove')"
                                  style="cursor: pointer;margin-left: 15px;"
                                  title="Remove Multiple Songs From This Album"
                              > - </i>
                              <i  class="fa fa-music text-info"
                                  data-keyboard="false"
                                  data-backdrop='static'
                                  @click="getSongList(band_album.id,'Sort')"
                                  style="cursor: pointer;margin-left: 15px;"
                                  title="Sort the sequence of the songs."
                              > <i class="fa fa-sort text-info"></i> </i>
                              <ul style="padding:0" v-if="band_album.song_lists.length > 0">
                                <li v-for="song,ind in band_album.song_lists" style="list-style: none;">
                                  <i  class="fa fa-trash text-danger"
                                      data-keyboard="false"
                                      data-backdrop='static'
                                      style="cursor: pointer;"
                                      title="Remove this song"
                                      @click="showDeleteSongForAlbum(band_album.id, band_album.album, song.id, song.title)"
                                  >
                                  </i>
                                  @{{song.title}}
                                </li>
                              </ul>
                            </td>
                            <td>@{{ band_album.release_date }}</td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="band_album.is_public"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <a
                                    :href="'/albums/' + band_album.id"
                                    type="button"
                                    class="btn btn-primary btn-circle"
                                >
                                    <i class="fa fa-music"></i>
                                </a>
                                <a
                                    :href="'/albums/'+band_album.id+'/edit'"
                                    type="button"
                                    class="btn btn-success btn-circle"
                                >
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button
                                    type="button"
                                    id="delete_details"
                                    :data-id="band_album.id"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#exampleModalCenter"
                                    @click="deleteAlbum(band_album.id)"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getAlbumList()"></pagination>
            </div>
        </div>

        <!-- Modals -->
        <div class="modal" id="selectSongs" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <form class="form-horizontal was-validated" @submit.prevent="processMultipleSong" >
                    <div class="modal-header">
                        <h4 class="modal-title">@{{task}} Songs - @{{album_name}}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body vld-parent">
                        <loading :active.sync="isLoadingSongList" :can-cancel="false" :is-full-page="false"></loading>

                        <span v-if="task != 'Sort'">
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


                                    @php /*
                                    <tbody v-if="allSongs.length > 0">
                                        <tr v-for="song,key in allSongs">
                                    */ @endphp

                                            <td>
                                                <input name="songsel" type="checkbox" :value="song.id" v-model="song.checked" :checked="song.checked">
                                            </td>
                                            <td>@{{song.title}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                      </span>
                      <span v-if="task === 'Sort'">
                          <p>You can just drag and drop the sequence of the  song.</p>
                            <draggable
                              :list="draggablesongLists"
                              :disabled="!enabled"
                              class="list-group"
                              ghost-class="ghost"
                              @change="draggableSortTrackSequence"
                              @start="dragging = true"
                              @end="dragging = false"
                            >
                               <div
                                 class="list-group-item"
                                 v-for="element in draggablesongLists"
                                 :key="element.title"
                                 style="cursor: move"
                               >
                                @{{ element.title }} <i class="fas fa-arrows-alt"></i>
                              </div>
                            </draggable>
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="submitting"
                            v-if="task != 'Sort'"
                        >@{{task}} Songs</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div class="modal" id="deleteSongForAlbum" tabindex="-1" role="dialog" aria-labelledby="deleteSongForAlbum" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            Remove
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to remove the song <u>@{{song_title}}</u> from the album <u>@{{album_name}}</u>?
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



</admin-index-album-component>
@stop
