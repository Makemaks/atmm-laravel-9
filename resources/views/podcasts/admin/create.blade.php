@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-podcast-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form
                id="podcast_form"
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
                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                id="inputEmail4"
                                placeholder="Title"
                                v-model="title"
                            />
                            <span class="control-label" v-if="title_error">Title is required</span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="type_error ? 'has-error has-feedback' : ''"
                    >
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="type" id="type_podcast" value="podcast" checked>
                                    <label class="form-check-label" for="type_podcast">
                                        Podcast
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="type" id="type_podcast" value="audiobook">
                                    <label class="form-check-label" for="type_podcast">
                                        Audiobook
                                    </label>
                                </div>
                                <span class="control-label" v-if="type_error">Type is required</span>
                            </div>
                        </div>
                    <div
                        class="form-group"
                        :class="date_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-8">
                            <input
                                type="date"
                                class="form-control"
                                name="date"
                                id="date"
                                v-model="date"
                            />
                            <span class="control-label" v-if="date_error">Date is required</span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="audio_error || audio_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Audio</label>
                        <div class="col-sm-8">
                            <div id="audio_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Audio</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other audio</a>
                                <input type="hidden" name="audio" id="audio_file" ref="audio_file" />
                            </div>
                            <span class="control-label" v-if="audio_error">Audio is required</span>
                            <span class="control-label" v-if="audio_validation_error">
                                File must be an audio
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
</admin-create-podcast-component>
@stop
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkPodcastUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#podcast_form'),
        container: $('#audio_uploader'),
        submitButton: $('#submit'),
				input: $('#audio_file'),
				mimeTypes : [
					{ title : "Audio files", extensions : "mp3,mpga" },
                ],
        multipartParams: {
            dir: 'podcasts'
        },
        required: true,
    });

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
            dir: 'podcasts/images'
        },
        required: true,
    });

</script>
@endsection
