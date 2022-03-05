@extends('layouts.layout-admin')
@section('page_heading','Instrumentals')
@section('section')
<admin-index-instrumental-component inline-template>
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
                <a type="button" href="/instrumentals/create" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-plus"></i>&nbsp; Create
                </a>
            </div>
        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
                <table class="table table-hover instrumental-table">
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
                        <th>IMAGE</th>
                        <th>HIGH KEY VIDEO</th>
                        <th>LOW KEY VIDEO</th>
                        <th>HIGH KEY AUDIO</th>
                        <th>LOW KEY AUDIO</th>
                        <th>
                            <a href="{{
                                generateQueryUrl('created_at')
                            }}">
                                DATE
                                @if(Request::input('sortBy') == 'created_at')
                                    <i class="fas {{ Request::input('sortOrder') == 'DESC' ? 'fa-chevron-down' : 'fa-chevron-up'  }}"></i>
                                @endif
                            </a>
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
                        @foreach($instrumentals as $instrumental)
                        <tr>
                            <td>{{$instrumental->title}}</td>
                            <td style="height: 50px; width: 50px;">
                                <a href="{{\Config::get('filesystems.aws_url').$instrumental->image}}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{\Config::get('filesystems.aws_url').$instrumental->image}}" alt="" srcset="" height="100%">
                                </a>
                            </td>
                            <td><a href="{{\Config::get('filesystems.aws_url').$instrumental->high_key_video}}" target="_blank" rel="noopener noreferrer">{{$instrumental->high_key_video}}</a></td>
                            <td><a href="{{\Config::get('filesystems.aws_url').$instrumental->low_key_video}}" target="_blank" rel="noopener noreferrer">{{$instrumental->low_key_video}}</a></td>
                            <td><a href="{{\Config::get('filesystems.aws_url').$instrumental->high_key_audio}}" target="_blank" rel="noopener noreferrer">{{$instrumental->high_key_audio}}</a></td>
                            <td><a href="{{\Config::get('filesystems.aws_url').$instrumental->low_key_audio}}" target="_blank" rel="noopener noreferrer">{{$instrumental->low_key_audio}}</a></td>
                            <td>
                                {{ $instrumental->formattedDate() }}
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$instrumental->is_public}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <div style="text-align: left;">
                                    <input
                                        type="checkbox"
                                        :checked="{{$instrumental->show_in_explore}}"
                                        onclick="return false"
                                    />
                                </div>
                            </td>
                            <td>
                                <a href="/instrumentals/{{$instrumental->id}}/edit" type="button" class="btn btn-success btn-circle">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button
                                    type="button"
                                    id="delete_details"
                                    data-id="{{$instrumental->id}}"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#exampleModalCenter"
                                    @click="deleteInstrumental('{{$instrumental->id}}')"
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
                {{ $instrumentals->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</admin-index-instrumental-component>
@stop
