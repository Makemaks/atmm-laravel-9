@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-album-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form id="band_album_form" class="form-horizontal" action="{{route('albums.update', $band_album->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Album Title</label>
                        <div class="col-sm-8">
                            <input type="text" name="album" value="{{$band_album->album}}" class="form-control" id="inputEmail4" placeholder="Album Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Artwork</label>
                        <div class="col-sm-8">
                            <div id="image_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Artwork</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other image</a>
                                <input type="hidden" name="image" id="image_file"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Liner</label>
                        <div class="col-sm-8">
                            <div id="file_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Liner</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other file</a>
                                <input type="hidden" name="liner" id="file_file" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Release Date</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="release_date" id="release_date" >
                                @for($year=1900; $year<=date('Y'); $year++)
                                    <option {{ $year == $band_album->release_date ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Release Date</label>
                        <div class="col-sm-8">
                            <div class="input-group date">
                                <input type="text" name="release_date" value="{{$band_album->release_date}}" class="form-control datepicker" id="inputEmail4" placeholder="Release Date" autocomplete="off">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <div class="form-control">
                                <div class="checkbox" style="padding-top: 3px;">
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="is_public"
                                            :checked="{{$band_album->is_public}}"
                                            value="true"
                                        >
                                        Check if you want to show it in public
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
</admin-edit-album-component>
@stop
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkImageUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#band_album_form'),
        container: $('#image_uploader'),
        submitButton: $('#submit'),
				input: $('#image_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "png,jpg,jpeg,gif" },
                ],
        multipartParams: {
            dir: 'albums/images'
        },
    });

    const chunkMusicUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#band_album_form'),
        container: $('#file_uploader'),
        submitButton: $('#submit'),
				input: $('#file_file'),
				mimeTypes : [
					{ title : "Doc files", extensions : "pdf,docx,dotx,dotm,docb" },
                ],
        multipartParams: {
            dir: 'album_liners'
        },
    });

</script>
@endsection