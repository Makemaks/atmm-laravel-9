@extends('layouts.layout-admin')
@section('page_heading','Songs')
@section('section')
<admin-index-music-component inline-template>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                    @include('fragments.search-list-form', ['default_field' => 'title'])
            </div>
            <div class="col-md-6 text-right">
                <a type="button" href="/songs/create" class="btn btn-primary">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div
            class="row panel panel-default vld-parent"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
                <table class="table table-hover custom-table-css">
                    <thead>
                        <th>
                            <a href="{{
                                generateQueryUrl('title')
                            }}">
                                TITLE
                                @if(!Request::has('sortBy') || Request::input('sortBy') == 'title')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>AUTHORS</th>
                        <th>IMAGE</th>
                        <th>ARTISTS</th>
                        <th>AUDIO</th>
                        <th>
                            <a href="{{
                                generateQueryUrl('is_public')
                            }}">
                                PUBLIC
                                @if(Request::input('sortBy') == 'is_public')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{
                                generateQueryUrl('show_in_explore')
                            }}">
                                EXPLORE
                                @if(Request::input('sortBy') == 'show_in_explore')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
                        </th>
                        <th width="12%">ACTIONS</th>
                    </thead>
                    <tbody>
                        @foreach($musics as $music)
                        <tr>
                            <td>{{$music->title}}</td>
                            <td>
                                <i  class="fa fa-user-plus text-info"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    @click="getAuthorArtistList({{$music->id}},'Add','author')"
                                    style="cursor: pointer;"
                                    title="Add Multiple Authors for this Song"
                                >
                                </i>
                                <i  class="fa fa-user text-info"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    @click="getAuthorArtistList({{$music->id}},'Remove','author')"
                                    style="cursor: pointer;margin-left: 15px;"
                                    title="Remove Multiple Authors for this Song"
                                >-</i>
                                @if (count($music->authors) > 0 )
                                    <ul style="padding:0">
                                    @foreach ($music->authors as $ind => $author)
                                        <li style="list-style: none;">
                                            <i  class="fa fa-trash text-danger"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                @click="showDeleteSongAuthorArtist({{$music->id}}, '{{ addslashes($music->title) }}', {{$author->id}}, '{{ addslashes($author->name) }}', 'author' )"
                                                style="cursor: pointer;"
                                                title="Remove this Author"
                                            >
                                            </i>
                                            {{ $author->name }}
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td style="height: 50px; width: 50px;">
                                <expandable-image
                                  class="image"
                                  src="{{\Config::get('filesystems.aws_url').$music->image}}"
                                  alt="{{$music->title}}"
                                  title="{{$music->title}}">
                                </expandable-image>
                            </td>


                            <td>
                              <i  class="fa fa-user-plus text-info"
                                  data-keyboard="false"
                                  data-backdrop='static'
                                  @click="getAuthorArtistList({{$music->id}},'Add','artist')"
                                  style="cursor: pointer;"
                                  title="Add Multiple Artists for this Song"
                              >
                              </i>
                              <i  class="fa fa-user text-info"
                                  data-keyboard="false"
                                  data-backdrop='static'
                                  @click="getAuthorArtistList({{$music->id}},'Remove','artist')"
                                  style="cursor: pointer;margin-left: 15px;"
                                  title="Remove Multiple Artists for this Song"
                              >-</i>
                              @if (count($music->artists) > 0 )
                                  <ul style="padding:0">
                                  @foreach ($music->artists as $ind => $artist)
                                      <li style="list-style: none;">
                                          <i  class="fa fa-trash text-danger"
                                              data-keyboard="false"
                                              data-backdrop='static'
                                              @click="showDeleteSongAuthorArtist({{$music->id}}, '{{ addslashes($music->title) }}', {{$artist->id}}, '{{ addslashes($artist->name) }}', 'artist' )"
                                              style="cursor: pointer;"
                                              title="Remove this Artist"
                                          >
                                          </i>
                                          {{ $artist->name }}
                                      </li>
                                  @endforeach
                                  </ul>
                              @endif
                            </td>


                            <td>
                                <audio
                                    id="song{{$music->id}}"
                                    data-id="{{$music->id}}"
                                    src="{{\Config::get('filesystems.aws_url')}}{{$music->audio}}"
                                    ref="song{{$music->id}}"
                                    style="height:30px"
                                >
                                </audio>
                                <button
                                    @click="playSong({{$music->id}})"
                                    class="btn btn-success btn-circle"
                                >
                                    <i
                                        v-if="play_id !== {{$music->id}} || !isPlaying"
                                        class="fas fa-play"
                                        data-toggle="tooltip"
                                        title="Play"
                                    ></i>
                                    <i
                                        v-else
                                        class="fas fa-pause"
                                        data-toggle="tooltip"
                                        title="Pause"
                                    ></i>
                                </button>

                                <a href="{{\Config::get('filesystems.aws_url')}}{{$music->audio}}" class="btn btn-success btn-circle" target="_blank">
                                    <i
                                        class="fas fa-external-link-alt"
                                        data-toggle="tooltip"
                                        title="Play in New Tab"
                                    ></i>
                                </a>
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$music->is_public}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$music->show_in_explore}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <a
                                    href="/songs/{{$music->id}}"
                                    type="button"
                                    class="btn btn-primary btn-circle"
                                >
                                    <i class="fa fa-search"></i>
                                </a>
                                <a href="/songs/{{$music->id}}/edit" type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button
                                    type="button"
                                    id="delete_details"
                                    data-id="{{$music->id}}"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#exampleModalCenter"
                                    @click="deleteMusic('{{$music->id}}')"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage"></loading>
        </div>
        <div class="row">
            <div style="float:right">
                {{ $musics->appends(request()->all())->links() }}
            </div>
        </div>

        <!-- Modals -->
        <div class="modal" id="selectAuthorArtist" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <form class="form-horizontal was-validated" @submit.prevent="processMultipleAuthorArtist" >
                    <div class="modal-header">
                        <h4 class="modal-title">@{{task}} @{{objlist}}s - @{{song_title}}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body vld-parent">
                        <loading :active.sync="isLoadingAuthorList" :can-cancel="false" :is-full-page="fullPage"></loading>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" v-model="search" placeholder="Search">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="button" style="padding:9px 15px">
                                <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <input type="button" value="Select All" class="btn btn-primary" @click="checkAll()">
                        <input type="button" value="Unselect All" class="btn btn-primary" @click="unCheckAll()">
                        <div style="overflow-y: auto;max-height:380px;">
                            <table class="table table-hover custom-table-css" >
                                <thead>
                                    <th colspan="2">
                                        <a href="javascript:void(0)" @click="toggleSort()">
                                            <i class="fa" :class="{ 'fa-chevron-up': sort === 'ASC', 'fa-chevron-down': sort === 'DESC' }"></i>
                                            @{{objlist}} Name
                                        </a>
                                    </th>
                                </thead>
                                <tbody v-if="filteredAuthorsArtists.length > 0">
                                    <tr v-for="author_artist,key in filteredAuthorsArtists">
                                        <td>
                                            <input name="author_artist_sel" type="checkbox" :value="author_artist.id" v-model="author_artist.checked" :checked="author_artist.checked">
                                        </td>
                                        <td>@{{author_artist.name}}</td>
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
                        >@{{task}} @{{objlist}}s</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="deleteSongAuthorArtist"
            tabindex="-1"
            role="dialog"
            aria-labelledby="deleteSongAuthor"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            Remove  @{{objlist}}
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to remove <u>@{{author_artist_name}}</u> in the song <u>@{{song_title}}</u>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form
                            @submit.prevent="removeAuthorArtist"
                        >
                        {{ csrf_field() }}
                            <button
                                type="submit"
                                class="btn btn-danger"
                                :disabled="submitting"
                            > Remove  @{{objlist}} </button>
                        </form>
                    </div>
                </div>
                <loading :active.sync="isLoadingRemoveAuthorArtist" :can-cancel="false" :is-full-page="true"></loading>
            </div>
        </div>
    </div>

</admin-index-music-component>
@stop
