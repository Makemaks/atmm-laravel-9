@extends('layouts.layout-admin')
@section('page_heading','Settings')

@section('style')
<style>
    .modal-lg {
        max-width: 1100px !important;
    }
    .modal-lg-new {
        max-width: 1000px !important;
        top: 150px !important;
    }
</style>
@endsection

@section('section')
<admin-index-settings-component inline-template>
    <div class="col-sm-12">
        <div class="row">
            <div class="panel-body">

              <loading :active.sync="isLoadingDetails" :can-cancel="false" :is-full-page="false"> </loading>
              <form @submit.prevent="saveSettings">
                <div class="form-group">
                  <label>Trial Days</label>
                  <input type="text" v-model="days_trial" value="{{ $days_trial }}" class="form-control" id="trialdays" name="trialdays" placeholder="Enter no of trial days">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>

            </div>
        </div>
    </div>
</admin-index-settings-component>
@stop
