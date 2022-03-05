@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-artist-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form class="form-horizontal" action="{{route('artists.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" id="inputEmail4" placeholder="Name" />
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
</admin-create-artist-component>
@stop
