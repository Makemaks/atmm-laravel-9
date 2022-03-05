@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-instrumental-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form
                    id="instrumental_form"
                    class="form-horizontal was-validated"
                    @submit.prevent="submit"
                >
                    @csrf
                    <div
                        class="form-group"
                        :class="title_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="title" v-model="title" />
                            <span class="control-label" v-if="title_error">
                                Title is required
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="image_error || image_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Image</label>
                        <div class="col-sm-8">
                            <div id="image_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Image</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other image</a>
                                <input type="hidden" name="image" id="image_file" ref="image_file" />
                            </div>
                            <span class="control-label" v-if="image_error">Image is required</span>
                            <span class="control-label" v-if="image_validation_error">
                                Please choose a valid image
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="high_key_video_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">High Key Video</label>
                        <div class="col-sm-8">
                            <div id="high_key_video_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Video</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other video</a>
                                <input type="hidden" name="high_key_video" id="high_key_video_file" ref="high_key_video_file" />
                            </div>
                            <span class="control-label" v-if="high_key_video_validation_error">
                                File must be a video
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="low_key_video_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Low Key Video</label>
                        <div class="col-sm-8">
                            <div id="low_key_video_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Video</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other video</a>
                                <input type="hidden" name="low_key_video" id="low_key_video_file" ref="low_key_video_file" />
                            </div>
                            <span class="control-label" v-if="low_key_video_validation_error">
                                File must be a video
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="high_key_audio_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">High Key Audio</label>
                        <div class="col-sm-8">
                            <div id="high_key_audio_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Audio</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other audio</a>
                                <input type="hidden" name="high_key_audio" id="high_key_audio_file" ref="high_key_audio_file" />
                            </div>
                            <span class="control-label" v-if="high_key_audio_validation_error">
                                File must be an audio
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="low_key_audio_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Low Key Audio</label>
                        <div class="col-sm-8">
                            <div id="low_key_audio_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Audio</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other audio</a>
                                <input type="hidden" name="low_key_audio" id="low_key_audio_file" ref="low_key_audio_file" />
                            </div>
                            <span class="control-label" v-if="low_key_audio_validation_error">
                                File must be an audio
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <div class="form-control">
                                <div class="checkbox" style="padding-top: 3px;">
                                    <label>
                                        <input type="checkbox" v-model="show_in_explore" value="true">
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
                                        <input type="checkbox" v-model="is_public" value="true">
                                        Check if you want to show it in public (Paid Subscribers)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <div class="progress">
                                <div
                                    class="progress-bar active"
                                    :class="uploadStatus === 'finalizing' ? 'progress-bar-striped' : ''"
                                    :style="'width:' + percentage + '%'"
                                >
                                    <span style="text-transform: uppercase;">
                                        <span v-if="uploadStatus === 'uploading...'">
                                            @{{ parseInt(percentage) }}%
                                        </span>
                                        @{{ uploadStatus }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button
                                type="submit"
                                id="submit"
                                class="btn btn-primary"
                                :disabled="submitting"
                            >Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</admin-create-instrumental-component>
@stop
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkImageUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#instrumental_form'),
        container: $('#image_uploader'),
        submitButton: $('#submit'),
				input: $('#image_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "png,jpg,jpeg,gif" },
                ],
        multipartParams: {
            dir: 'instrumentals/images'
        },
        required: true,
    });

    const chunkHighKeyVideoUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#instrumental_form'),
        container: $('#high_key_video_uploader'),
        submitButton: $('#submit'),
				input: $('#high_key_video_file'),
				mimeTypes : [
					{ title : "Video files", extensions : "mp4,MP2T,3gpp,quicktime,avi,mkv" },
                ],
        multipartParams: {
            dir: 'instrumentals'
        },
        required: true,
    });

    const chunkLowKeyVideoUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#instrumental_form'),
        container: $('#low_key_video_uploader'),
        submitButton: $('#submit'),
				input: $('#low_key_video_file'),
				mimeTypes : [
					{ title : "Video files", extensions : "mp4,MP2T,3gpp,quicktime,avi,mkv" },
                ],
        multipartParams: {
            dir: 'instrumentals'
        },
        required: true,
    });

    const chunkHighKeyAudioUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#instrumental_form'),
        container: $('#high_key_audio_uploader'),
        submitButton: $('#submit'),
				input: $('#high_key_audio_file'),
				mimeTypes : [
					{ title : "Audio files", extensions : "mpga,mp3" },
                ],
        multipartParams: {
            dir: 'instrumentals'
        },
        required: true,
    });

    const chunkLowKeyAudioUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#instrumental_form'),
        container: $('#low_key_audio_uploader'),
        submitButton: $('#submit'),
				input: $('#low_key_audio_file'),
				mimeTypes : [
					{ title : "Audio files", extensions : "mpga,mp3" },
                ],
        multipartParams: {
            dir: 'instrumentals'
        },
        required: true,
    });

</script>
@endsection