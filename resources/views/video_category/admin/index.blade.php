@extends('layouts.layout-admin')
@section('page_heading','Video Categories')
@section('section')
<admin-index-video-category-component inline-template>
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
                <searched :search="search" @gosearch="getVideoCategoryList('search')"></searched>
            </div>
            <div class="col-md-6 text-right">
                <a type="button" class="btn btn-danger">
                    <i class="fa fa-trash"></i>&nbsp; Delete
                </a>
                <a type="button" href="/video-categories/create" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
              <loading :active.sync="isLoadingVideoCategoryList" :can-cancel="false" :is-full-page="false"> </loading>
              <table class="table table-hover custom-table-css">
                  <thead>
                      <th><input name="" type="checkbox"></th>
                      <th>
                        <a href="#" @click="sortVideoCategoryList('description')">
                            DESCRIPTION
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>VIDEOS</th>
                      <th>ACTION</th>
                  </thead>
                  <tbody v-if="allVideoCategories.length > 0">
                      <tr v-for="video_category,key in allVideoCategories">
                          <td><input type="checkbox" v-model="video_category.checked" :checked="video_category.checked"></td>
                          <td>@{{video_category.description}}</td>
                          <td>
                              <ul style="padding:0" v-if="video_category.video_details.length > 0">
                                <li v-for="videoDetail,vdkey in video_category.video_details">
                                  @{{ videoDetail.title }}
                                </li>
                              </ul>
                          </td>
                          <td>
                            <a :href="'/video-categories/'+video_category.id+'/edit'" type="button" class="btn btn-success btn-circle">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            <button
                                type="button"
                                id="delete_details"
                                :data-id="video_category.id"
                                class="btn btn-danger btn-circle delete_details"
                                data-toggle="modal_test"
                                data-backdrop='static'
                                data-keyboard="false"
                                data-target="#exampleModalCenter"
                                @click="deleteVideoCategory(video_category.id,video_category.video_details.length)"
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
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getVideoCategoryList()"></pagination>
            </div>
        </div>
    </div>
</admin-index-video-category-component>
@stop
