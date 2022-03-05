@extends('layouts.layout-admin')
@section('page_heading','App Login History')
@section('section')
<admin-index-apploginhistory-component inline-template>
    <div class="col-sm-12">

        <div class="row">
            <div class="col-md-6">
                    <searched :search="search" @gosearch="getAppLoginHistoryList('search')"></searched>
            </div>
        </div>
        <div class="row panel panel-default" style="margin-top: 20px;" >
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingAppLoginHistoryList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover custom-table-css" >
                    <thead>
                        <th>USER</th>
                        <th>OS</th>
                        <th>VERSION</th>
                        <th>DATE TIME</th>
                        <th>DEVICE FULL INFO</th>
                    </thead>
                    <tbody v-if="allAppLoginHistory.length > 0">
                        <tr v-for="login_history,key in allAppLoginHistory">
                            <td>@{{ login_history.user.name }}</td>
                            <td>@{{ login_history.device_os }}</td>
                            <td>@{{ login_history.device_version }}</td>
                            <td>@{{ login_history.created_at }}</td>
                            <td>
                                <button
                                    type="button"
                                    id="delete_details"
                                    class="btn"

                                    alt="View Full Info"
                                    title="View Full Info"
                                    @click="viewDeviceFullInfo(login_history.id)"
                                >
                                    <i class="fas fa-search"></i></i> View
                                </button>

                                <!-- Modals -->
                                <div class="modal" :id="'deviceFullInfo_' + login_history.id" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                        <form class="form-horizontal was-validated" @submit.prevent="processMultipleSong" >
                                            <div class="modal-header">
                                                <h4 class="modal-title">Device Full Info</h4>
                                                <button type="button" class="close" data-bd-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body vld-parent1">
                                              <div :id="login_history.id">
                                                <json-tree :raw="login_history.device_fullinfo"></json-tree>
                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getAppLoginHistoryList()"></pagination>
            </div>
        </div>



    </div>

</admin-index-apploginhistory-component>
@stop
