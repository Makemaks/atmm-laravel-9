@extends('layouts.layout-admin')
@section('page_heading','Royalty Information')

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
<admin-index-subscriber-metrics-component inline-template>
    <div class="col-sm-12">
        <div class="row">
            <form class="form-horizontal was-validated" @submit.prevent="getRoyalyInfoList" >
                <div class="col-md-12">
                    <h5><b>Select Date Range</b></h5>
                </div>
                <div class="col-md-3">
                    <input
                        type="date"
                        class="form-control"
                        name="date_start"
                        id="date_start"
                        ref="date_start"
                        v-model="date_start"
                    >
                </div>
                <div class="col-md-3">
                    <input
                        type="date"
                        class="form-control"
                        name="date_end"
                        id="date_end"
                        ref="date_end"
                        v-model="date_end"
                    >
                </div>
                <div class="col-md-1">
                    <button
                        type="submit"
                        id="submit"
                        class="btn btn-primary"
                        :disabled="submitting"
                    >Submit</button>
                </div>
            </form>
        </div>

        <div
            class="row panel panel-default vld-parent"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
                    <table class="table table-hover custom-table-css" >
                        <thead>
                            <th>Author</th>
                            <th>Royalties Due</th>
                            <th>Song Streams</th>
                            <th>Action</th>
                        </thead>
                        <tbody v-if="all_results.length > 0">
                            <tr v-for="result,key in all_results">
                                <td>@{{result.author_name}}</td>
                                <td>@{{result.royalty_value}}</td>
                                <td>@{{result.songs_streamed}}</td>                                
                                <td>
                                    <span v-if="result.songs_streamed > 0">
                                        <a type="button"
                                            class="btn btn-primary btn-circle"
                                            data-toggle="modal"
                                            data-target="#subscriberMetricsDetails"
                                            @click="showSubscriberMetricsDetails(result.author_id)"
                                            data-backdrop="static"
                                        >
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>

            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage"> </loading>

        </div>
        <div class="row">
            <div style="float:right">

            </div>
        </div>

        <!-- Modals -->
        <div class="modal" id="subscriberMetricsDetails" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body vld-parent">
                    <loading :active.sync="isLoadingDetails" :can-cancel="false" :is-full-page="fullPage"> </loading>
                    <table class="table table-hover custom-table-css" >
                        <thead>
                            <th>Song</th>
                            <th>User / Email</th>
                            <th>IP</th>
                            <!--  <th>Streamed Count</th> -->
                            <th width="15%">Time Streamed</th> 
                            <th width="15%">Author(s)</th>
                            <th width="15%">Royalties Due</th>
                        </thead>
                        <tbody v-if="all_details_results.length > 0">
                            <tr v-for="result,key in all_details_results">
                                <td>@{{result.song}}</td>
                                <td>
                                    @{{result.name}} <br>
                                    @{{result.email}}
                                </td>
                                <td>
                                    <a  href
                                        data-toggle="modal"
                                        data-backdrop='static'
                                        data-keyboard="false"
                                        data-target="#ip_details"
                                        @click="showIPAdressDetails(result.ip_adress)"
                                    >
                                        @{{result.ip_adress}}
                                    </a>
                                </td>
                                <!-- <td align="center">@{{result.songs_streamed}}</td> -->
                                <td>@{{result.time_streamed}}</td>
                                <td>
                                    <ul style="padding:0">
                                        <li v-for="value,key in result.authors">@{{value.name}}</li>
                                    </ul>
                                </td>
                                <td>@{{result.royalty_due}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td v-for="n  in 6">
                                    <span v-if="n == 5"><b>Total Royalty Due</b></span>
                                    <span v-if="n == 6"><b>@{{total_royalty_due}}</b></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ip_details" tabindex="-1" role="dialog" aria-labelledby="ip_details" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-lg-new" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            IP Address Details - @{{ip_address}}
                        </h4>
                    </div>
                    <div class="modal-body vld-parent">
                         <div style="overflow: auto;max-height: 450px;min-height: 200px;">
                            <loading :active.sync="isLoadingIPAddressDetails" :can-cancel="false" :is-full-page="false"> </loading>
                            <div v-html="ip_address_details" ></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</admin-index-subscriber-metrics-component>
@stop
