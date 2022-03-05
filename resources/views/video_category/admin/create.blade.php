@extends('layouts.layout-admin')
@section('page_heading','Create')
@section('section')
<admin-create-video-category-component inline-template>
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.flash_messages')
                <form
                    class="form-horizontal was-validated"
                    @submit.prevent="submit"
                >
                    @csrf
                    <div
                        class="form-group"
                        :class="description_error ? 'has-error has-feedback' : ''"
                    >
                        <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <input
                                type="text"
                                name="description"
                                class="form-control"
                                id="inputEmail4"
                                placeholder="Description"
                                v-model="description"
                            >
                            <span class="control-label" v-if="description_error">Description is required</span>
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
</admin-create-video-category-component>
@stop
