@extends('layouts.layout-admin')
@section('page_heading','Videos')
@section('section')
<admin-index-video-component inline-template>
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
                @include('fragments.search-list-form', ['default_field' => 'title'])
            </div>
            <div class="col-md-6 text-right">
                <a type="button" href="/videos/create" class="btn btn-primary" data-dismiss="modal">
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
                        <th>IMAGE</th>
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
                        <th>VIDEO CATEGORY</th>
                        <th>
                            <a href="{{
                                generateQueryUrl('date_release')
                            }}">
                                DATE RELEASE
                                @if(Request::input('sortBy') == 'date_release')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            VIDEO
                        </th>
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
                        <th>ACTION</th>
                    </thead>
                    <tbody>
                        @foreach($videos as $video)
                        <tr>
                            <td style="height: 50px; width: 50px;">
                                @if($video->image)
                                <expandable-image
                                  class="image"
                                  src="{{\Config::get('filesystems.aws_url').$video->image}}"
                                  alt="{{$video->title}}"
                                  title="{{$video->title}}">
                                </expandable-image>
                                @endif
                            </td>
                            <td>{{$video->title}}</td>
                            <td>{{$video->videoCategory->description}}</td>
                            <td>{{$video->date_release}}</td>
                            <td>
                                <a href="{{\Config::get('filesystems.aws_url')}}{{$video->video}}" target="_blank" rel="noopener noreferrer">{{$video->video}}</a>
                                <a onclick="$('#other_video_resolution_{{$video->id}}').toggle('slow');"class="btn btn-success btn-circle"><i class="fa fa-arrows-alt-v"></i></a>
                                <div id="other_video_resolution_{{$video->id}}" style="display: none;">
                                    <b>480: </b>
                                    <a href="{{\Config::get('filesystems.aws_url')}}{{$video->video_480}}" target="_blank" rel="noopener noreferrer">{{$video->video_480}}</a><br>
                                    <b>720: </b>
                                    <a href="{{\Config::get('filesystems.aws_url')}}{{$video->video_720}}" target="_blank" rel="noopener noreferrer">{{$video->video_720}}</a><br>
                                    <b>1080: </b>
                                    <a href="{{\Config::get('filesystems.aws_url')}}{{$video->video_1080}}" target="_blank" rel="noopener noreferrer">{{$video->video_1080}}</a><br>
                                    <b>Compress: </b>
                                    <a href="{{\Config::get('filesystems.aws_url')}}{{$video->video_default}}" target="_blank" rel="noopener noreferrer">{{$video->video_default}}</a><br>
                                </div>
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$video->is_public}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$video->show_in_explore}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <a href="/videos/{{$video->id}}/edit" type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button
                                    type="button"
                                    id="delete_details"
                                    data-id="{{$video->id}}"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#exampleModalCenter"
                                    @click="deleteVideo('{{$video->id}}')"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div style="float:right">
                {{ $videos->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</admin-index-video-component>
@stop
