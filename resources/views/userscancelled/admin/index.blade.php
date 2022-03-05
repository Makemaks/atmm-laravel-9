@extends('layouts.layout-admin')
@section('page_heading','Users who cancelled there subscription')

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
<admin-index-userscancelled-component inline-template>
    <div class="col-sm-12">

        <div class="row">
          <div class="col-md-6">
            <searched :search="search" @gosearch="getCancelledUserList('search')"></searched>
          </div>
        </div>
        <div class="row panel panel-default" style="margin-top: 20px;" >
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingCancelledUserList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover custom-table-css" >
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Reason to Stop</th>
                        <th>Date Registered</th>
                        <th>Date Cancelled</th>
                    </thead>
                    <tbody v-if="allCancelledUsers.length > 0">
                        <tr v-for="userscancelled,key in allCancelledUsers">

                            <td>@{{ userscancelled.user.name }}</td>
                            <td>@{{ userscancelled.email }}</td>
                            <td>@{{ userscancelled.reason_to_stop }}</td>
                            <td>@{{ userscancelled.user.created_at }}</td>
                            <td>@{{ userscancelled.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getCancelledUserList()"></pagination>
            </div>
        </div>




    </div>

</admin-index-userscancelled-component>
@stop
