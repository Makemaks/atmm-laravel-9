@extends('layouts.layout-admin')
@section('page_heading','Podcasts')
@section('section')
<admin-index-podcast-component inline-template>
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
                <a type="button" href="/podcasts/create" class="btn btn-primary" data-dismiss="modal">
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
                            <a href="{{
                                generateQueryUrl('title')
                            }}">
                                TITLE
                                @if(!Request::has('sortBy') || Request::input('sortBy') == 'title')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{
                                generateQueryUrl('date')
                            }}">
                                DATE
                                @if(Request::input('sortBy') == 'date')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
                        </th>
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
                        <th>ACTION</th>
                    </thead>
                    <tbody>
                        @foreach($podcasts as $podcast)
                        <tr>
                            <td>{{$podcast->title}}</td>
                            <td>{{$podcast->date}}</td>
                            <td><a href="{{\Config::get('filesystems.aws_url').$podcast->audio}}" target="_blank" rel="noopener noreferrer">{{$podcast->audio}}</a></td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$podcast->is_public}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$podcast->show_in_explore}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <a href="/podcasts/{{$podcast->id}}/edit" type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button
                                    type="button"
                                    id="delete_details"
                                    data-id="{{$podcast->id}}"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#exampleModalCenter"
                                    @click="deletePodcast('{{$podcast->id}}')"
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
                {{ $podcasts->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</admin-index-podcast-component>
@stop
