@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-video-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form
                    action="{{route('videos.update', $video_detail->id)}}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="form-horizontal"
                    id="edit_video_form"
                >
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail4" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_method" value="PUT">
                            <input
                                type="text"
                                name="title"
                                value="{{$video_detail->title}}"
                                class="form-control"
                                id="inputEmail4"
                                placeholder="Title"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputState" class="col-sm-2 control-label">Video Category</label>
                        <div class="col-sm-8">
                            <select
                                id="category"
                                class="form-control"
                                name="video_category_id"
                            >
                                <option selected>----- Choose Category -----</option>
                                @foreach ($video_categories as $video_category)
                                    <option value="{{$video_category->id}}" {{$video_detail->video_category_id === $video_category->id ? 'selected="selected"' : '' }}>{{$video_category->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCity" class="col-sm-2 control-label">
                            Date Release
                        </label>
                        <div class="col-sm-8">
                            <input
                                type="date"
                                class="form-control"
                                name="date_release"
                                value="{{$video_detail->date_release}}"
                                id="date_release"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Image</label>
                        <div class="col-sm-8">
                            <div id="image_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Image</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other image</a>
                                <input type="hidden" name="image" id="image_file" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCity" class="col-sm-2 control-label">

                        </label>
                        <div class="col-sm-8">
                            <div id="upload_video_container">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Video</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other video</a>
                                <input type="hidden" name="video" id="video_file" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <div class="form-control">
                                <div class="checkbox" style="padding-top: 3px;">
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="show_in_explore"
                                            :checked="{{$video_detail->show_in_explore}}"
                                            value="true"
                                        >
                                        Check if you want to show it in the explore page
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <div class="form-control">
                                <div class="checkbox" style="padding-top: 3px;">
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="is_public"
                                            :checked="{{$video_detail->is_public}}"
                                            value="true"
                                        >
                                        Check if you want to show it in public (Paid Subscribers)
                                    </label>
                                </div>
                            </div>
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
</admin-edit-video-component>
@endsection
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkImageUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#edit_video_form'),
        container: $('#image_uploader'),
        submitButton: $('#submit'),
				input: $('#image_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "png,jpg,jpeg,gif" },
                ],
        multipartParams: {
            dir: 'videos/images'
        }
    });

    const chunkUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        container: $('#upload_video_container'),
        form: $('#edit_video_form'),
        submitButton: $('#submit'),
        input: $('#video_file'),
        mimeTypes : [
            { title : "Image files", extensions : "mp4,wmv,mkv,avi" },
        ],
        multipartParams: {
            dir: 'videos'
        }
    });

</script>
@endsection
