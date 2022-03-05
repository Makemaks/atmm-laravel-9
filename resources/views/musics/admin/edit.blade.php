@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-music-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form class="form-horizontal" id="edit_music_form" action="{{route('songs.update', $music->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="text" name="title" value="{{$music->title}}" class="form-control" id="inputEmail4" placeholder="Title">
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="date_release" value="{{$music->date_release}}" id="date_release" value="{{date('Y-m-d')}}">
                        </div>
                    </div> --}}
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
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Audio</label>
                        <div class="col-sm-8">
                            <div id="audio_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Audio</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other audio</a>
                                <input type="hidden" name="audio" id="audio_file" />
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
                                            :checked="{{$music->show_in_explore}}"
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
                                            :checked="{{$music->is_public}}"
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
</admin-edit-music-component>
@stop
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkImageUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#edit_music_form'),
        container: $('#image_uploader'),
        submitButton: $('#submit'),
				input: $('#image_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "png,jpg,jpeg,gif" },
                ],
        multipartParams: {
            dir: 'audios/images'
        }
    });

    const chunkMusicUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#edit_music_form'),
        container: $('#audio_uploader'),
        submitButton: $('#submit'),
				input: $('#audio_file'),
				mimeTypes : [
					{ title : "Audio files", extensions : "mp3,mpga" },
                ],
        multipartParams: {
            dir: 'audios'
        }
    });

</script>
@endsection