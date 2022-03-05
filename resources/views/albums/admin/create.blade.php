@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-album-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 30px;">
                @include('layouts.flash_messages')
                <form
                    id="band_album_form"
                    class="form-horizontal was-validated"
                    @submit.prevent="submit"
                >
                    @csrf
                    <div
                        class="form-group"
                        :class="album_error ? 'has-error has-feedback' : ''"
                    >
                        <label
                            for="inputEmail3"
                            class="col-sm-2 control-label"
                        >Album Title</label>
                        <div class="col-sm-8">
                            <input
                                type="text"
                                name="album"
                                class="form-control"
                                id="inputEmail4"
                                placeholder="Album Title"
                                v-model="album"
                            />
                            <span class="control-label" v-if="album_error">
                                Album Title is required
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="image_error || image_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Artwork</label>
                        <div class="col-sm-8">
                            <div id="image_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Artwork</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other image</a>
                                <input type="hidden" name="image" id="image_file" ref="image_file" />
                            </div>
                            <span class="control-label" v-if="image_error">Artwork is required</span>
                            <span class="control-label" v-if="image_validation_error">
                                Artwork must be an image
                            </span>
                        </div>
                    </div>
                    <div
                        class="form-group"
                        :class="file_error || file_validation_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose Liner</label>
                        <div class="col-sm-8">
                            <div id="file_uploader">
                                <a class="btn btn-primary btn-sm browse" href="javascript:;">Select Liner</a>
                                <span class="selected_file"></span>&nbsp;
                                <strong class="upload_status"></strong>
                                <a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other file</a>
                                <input type="hidden" name="liner" id="file_file" ref="file_file" />
                            </div>
                            <span class="control-label" v-if="file_error">Liner is required</span>
                            <span class="control-label" v-if="file_validation_error">
                                Liner must be a document
                            </span>
                        </div>
                    </div>
                    <div
						class="form-group"
						:class="release_date_error ? 'has-error has-feedback' : ''"
					>
						<label for="inputCity" class="col-sm-2 control-label">Release Date</label>
						<div class="col-sm-8">
							<select class="form-control" name="release_date" id="release_date" v-model="release_date">
								@for($year=1900; $year<=date('Y'); $year++)
									<option>{{ $year }}</option>
								@endfor
							</select>
							<span class="control-label" v-if="release_date_error">Release Date is required</span>
						</div>
					</div>
                    {{-- <div
                        class="form-group"
                        :class="release_date_error ? 'has-error has-feedback' : ''"
                    >
                        <label
                            for="inputEmail3"
                            class="col-sm-2 control-label"
                        >Release Date</label>
                        <div class="col-sm-8">
                            <div class="input-group date">
                                <input
                                    type="text"
                                    name="release_date"
                                    class="form-control datepicker"
                                    id="inputEmail4"
                                    placeholder="Release Date"
                                    v-model="release_date"
                                    autocomplete="off"
                                />
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                            <span class="control-label" v-if="release_date_error">
                                Release Date is required
                            </span>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <div class="form-control">
                                <div class="checkbox" style="padding-top: 3px;">
                                    <label>
                                        <input type="checkbox" v-model="is_public" value="true">
                                        Check if you want to show it in public
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
</admin-create-album-component>
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
        required: true,
    });

    const chunkMusicUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#band_album_form'),
        container: $('#file_uploader'),
        submitButton: $('#submit'),
				input: $('#file_file'),
				mimeTypes : [
					{ title : "Sheet files", extensions : "pdf,docx,dotx,dotm,docb" },
                ],
        multipartParams: {
            dir: 'album_liners'
        },
        required: true,
    });

</script>
@endsection