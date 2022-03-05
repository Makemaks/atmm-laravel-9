@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-podcast-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form id="podcast_form" class="form-horizontal" action="{{route('podcasts.update', $podcast->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="text" name="title" value="{{$podcast->title}}" class="form-control" id="inputEmail4" placeholder="Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-8">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_podcast" value="podcast" {{ $podcast->type == 'podcast' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_podcast">
                                    Podcast
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_podcast" value="audiobook" {{ $podcast->type == 'audiobook' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_podcast">
                                    Audiobook
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="date" value="{{$podcast->date}}" id="date" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Audio</label>
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
                                            :checked="{{$podcast->show_in_explore}}"
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
                                            :checked="{{$podcast->is_public}}"
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
</admin-edit-podcast-component>
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
        }
    });

</script>
@endsection