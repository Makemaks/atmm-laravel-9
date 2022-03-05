@extends('layouts.layout-admin')
@section('page_heading','Edit')
@section('section')
<admin-edit-artist-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form class="form-horizontal" action="{{route('artists.update', $artist->id)}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="_method" value="PUT">
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                id="inputEmail4"
                                placeholder="Name"
                                value="{{ $artist->name }}"
                            />
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
</admin-edit-artist-component>
@stop
