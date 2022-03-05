@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-sheet-music-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form
                    id="sheet_music_form"
                    class="form-horizontal"
                    action="{{route('sheet_musics.update', $sheet_music->id)}}"
                    method="POST"
                >
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_method" value="PUT">
                            <input
                                type="text"
                                name="title"
                                value="{{$sheet_music->title}}"
                                class="form-control"
                                id="inputEmail4"
                                placeholder="Title"
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
                                <input type="hidden" name="image" id="image_file"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                        <div class="col-sm-8">
                            <div id="file_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select File</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other file</a>
                                <input type="hidden" name="file" id="file_file" />
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
                                            :checked="{{$sheet_music->show_in_explore}}"
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
                                            :checked="{{$sheet_music->is_public}}"
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
</admin-edit-sheet-music-component>
@stop
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkImageUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#sheet_music_form'),
        container: $('#image_uploader'),
        submitButton: $('#submit'),
				input: $('#image_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "png,jpg,jpeg,gif" },
                ],
        multipartParams: {
            dir: 'sheet_musics/images'
        },
    });

    const chunkMusicUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#sheet_music_form'),
        container: $('#file_uploader'),
        submitButton: $('#submit'),
				input: $('#file_file'),
				mimeTypes : [
					{ title : "Doc files", extensions : "pdf,docx,dotx,dotm,docb" },
                ],
        multipartParams: {
            dir: 'sheet_musics'
        },
    });

</script>
@endsection