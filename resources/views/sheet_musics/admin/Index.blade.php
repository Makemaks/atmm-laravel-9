@extends('layouts.layout-admin')
@section('page_heading','Sheet Music')
@section('section')
<admin-index-sheet-music-component inline-template>
    <div class="col-sm-12">
        <div class="modal fade" id="deleteSheetMusic" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
              <searched :search="search" @gosearch="getSheetMusicList('search')"></searched>
            </div>
            <div class="col-md-6 text-right">
                <a type="button" href="/sheet_musics/create" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingSheetMusicList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover instrumental-table">
                    <thead>
                        <th>
                            <a href="#" @click="sortSheetMusicList('title')">
                                TITLE
                                <span v-if="sort_field == 'title'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'title'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th>IMAGE</th>
                        <th>FILE</th>
                        <th style="width: 200px;">
                            <a href="#" @click="sortSheetMusicList('created_at')">
                                DATE
                                <span v-if="sort_field == 'created_at'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'created_at'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th>
                            <a href="#" @click="sortSheetMusicList('is_public')">
                                PUBLIC
                                <span v-if="sort_field == 'is_public'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'is_public'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th>
                            <a href="#" @click="sortSheetMusicList('show_in_explore')">
                                EXPLORE
                                <span v-if="sort_field == 'show_in_explore'">
                                  <i :class="'fas ' + ( (sort_order == 'asc') ? 'fa-chevron-down':'fa-chevron-up') + '' "></i>
                                </span>
                                <span v-if="sort_field != 'show_in_explore'"> <i class="fas fa-chevron-down"></i> </span>
                            </a>
                        </th>
                        <th style="width: 200px;">ACTION</th>
                    </thead>
                    <tbody v-if="allSheetMusics.length > 0">
                        <tr v-for="sheet_music,key in allSheetMusics">
                            <td>@{{sheet_music.title}}</td>
                            <td style="height: 50px; width: 50px;">
                              <a :href="aws_url + sheet_music.image" target="_blank" rel="noopener noreferrer">
                                  <img :src="aws_url + sheet_music.image" alt="" srcset="" height="100%">
                              </a>
                            </td>
                            <td>
                                <a :href="aws_url + sheet_music.file" target="_blank" rel="noopener noreferrer">@{{sheet_music.file}}</a>
                            </td>
                            <td>
                                @{{sheet_music.created_at}}
                            </td>
                            <td>
                              <div style="text-align: left;">
                                  <input
                                      type="checkbox"
                                      :checked="sheet_music.is_public"
                                      onclick="return false"
                                  />
                              </div>
                            </td>
                            <td>
                              <div style="text-align: left;">
                                  <input
                                      type="checkbox"
                                      :checked="sheet_music.show_in_explore"
                                      onclick="return false"
                                  />
                              </div>
                            </td>
                            <td>
                              <a :href="'/sheet_musics/'+sheet_music.id+'/edit'" type="button" class="btn btn-success btn-circle">
                                  <i class="fa fa-pencil-alt"></i>
                              </a>
                              <button
                                  type="button"
                                  id="delete_details"
                                  class="btn btn-danger btn-circle delete_details"
                                  data-toggle="modal"
                                  data-backdrop='static'
                                  data-keyboard="false"
                                  data-target="#deleteSheetMusic"
                                  @click="deleteSheetMusic(sheet_music.id)"
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
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getSheetMusicList()"></pagination>
            </div>
        </div>
    </div>
</admin-index-sheet-music-component>
@stop
