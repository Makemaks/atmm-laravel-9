@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-video-category-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form class="form-horizontal" action="{{route('video-categories.update', $video_category->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" value="{{$video_category->description}}" name="description" class="form-control" id="inputEmail4" placeholder="Description">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</admin-edit-video-category-component>
@stop
