@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-video-component inline-template>
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-body">
				@include('layouts.flash_messages')
				<form
					@submit.prevent="submit"
					class="form-horizontal was-validated"
					id="create_form_video"
				>
					@csrf
					<div
						class="form-group"
						:class="title_error ? 'has-error has-feedback' : ''"
					>
						<label for="inputEmail4" class="col-sm-2 control-label">Title</label>
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
						:class="video_category_id_error ? 'has-error has-feedback' : ''"
					>
						<label for="inputState" class="col-sm-2 control-label">Video Category</label>
						<div class="col-sm-8">
							<select
								id="category"
								class="form-control"
								name="video_category_id"
								v-model="video_category_id"
							>
								<option selected value="">----- Choose Category -----</option>
								@foreach ($video_categories as $video_category)
									<option value="{{$video_category->id}}">
										{{$video_category->description}}
									</option>
								@endforeach
							</select>
							<span class="control-label" v-if="video_category_id_error">Video Category is required</span>
						</div>
					</div>
					<div
						class="form-group"
						:class="date_release_error ? 'has-error has-feedback' : ''"
					>
						<label for="inputCity" class="col-sm-2 control-label">Date Release</label>
						<div class="col-sm-8">
							<input
								type="date"
								class="form-control"
								name="date_release"
								id="date_release"
								ref="date_release"
								v-model="date_release"
							>
							<span class="control-label" v-if="date_release_error">Date Release is required</span>
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
                                File must be an image
                            </span>
                        </div>
                    </div>
					<div
						class="form-group"
						:class="video_error || video_validation_error ? 'has-error has-feedback' : ''"
					>
							<label for="inputCity" class="col-sm-2 control-label">

							</label>
							<div class="col-sm-8">
									<div id="upload_video_container">
										<a class="btn btn-primary btn-sm browse" href="javascript:;">Select Video</a>
										<span class="selected_file"></span>&nbsp;
										<strong class="upload_status"></strong>
										<a class="cancel_upload" href="javascript:;" style="display:none;color:red;font-weight:bold;">choose other video</a>
										<input type="hidden" name="video" id="video_file" ref="video_file" />
									</div>
									<span class="control-label" v-if="video_error">Video is required</span>
									<span class="control-label" v-if="video_validation_error">
										File must be a video
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
								ref="submit"
							>Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</admin-create-video-component>
@stop
@section('script')
<script type="text/javascript" src="/js/chunkUploader.js"></script>
<script type="text/javascript">

    const chunkImageUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
        form: $('#create_form_video'),
        container: $('#image_uploader'),
        submitButton: $('#submit'),
				input: $('#image_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "png,jpg,jpeg,gif" },
                ],
        multipartParams: {
            dir: 'videos/images'
        },
        required: true,
    });

    const chunkUploader = new ChunkUploader({
        url: '{{ env("APP_ENV") === "production" ?  "//dev.songwritersundayschool.com/api/chunk-upload?prod=true" : "/api/chunk-upload"}}',
				container: $('#upload_video_container'),
        form: $('#create_form_video'),
        submitButton: $('#submit'),
				input: $('#video_file'),
				mimeTypes : [
					{ title : "Image files", extensions : "mp4,wmv,mkv,avi" },
				],
				multipartParams: {
            dir: 'videos'
				},
				required: true,
    });

</script>
@endsection
