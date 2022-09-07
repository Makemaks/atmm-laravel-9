@extends('layouts.layout-admin')
@section('page_heading','App Activity Log')

@section('style')
<style>
    .modal-lg {
        max-width: 1100px !important;
    }
    .modal-lg-new {
        max-width: 1000px !important;
        top: 150px !important;
    }
    .closebtn {
      margin-left: 18px;
      font-weight: bold;
      font-size: 22px;
      line-height: 20px;
      cursor: pointer;
      transition: 0.3s;
    }
    .alert {
      width: 684px;
    }
</style>
@endsection

@section('section')
<admin-index-appactivitylog-component inline-template>
    <div class="col-sm-12">
      @if (Session::has('messageAppActError'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i>
          &nbsp; {{ Session::get('messageAppActError') }}
          <span class="closebtn" style="color: red;" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
      @endif
      @if (Session::has('messageAppActSuccess'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i>
          &nbsp; {{ Session::get('messageAppActSuccess') }}
          <span class="closebtn" style="color: green;" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
      @endif
        <div class="row">
          <div class="col-md-6">
            <searched :search="search" @gosearch="getAppActivityLogList('search')"></searched>
          </div>
          <div class="col-mb-6">
              <select v-model="select_user" name="select_user" id="select_user" @change="filterByAppActivityLogList()">
                <option value="">--- All User ---</option>
                <option v-for="user,key in users" :value="user.user_id">
                      @{{  (user.user.name) }}
                </option>
              </select>

              <select v-model="select_os" name="select_os" id="select_os" @change="filterByAppActivityLogList()">
                <option value="">--- All OS ---</option>
                <option v-for="os,key in all_os" :value="os.device_os">
                      @{{  (os.device_os) }}
                </option>
              </select>

              <select v-model="select_device_name" name="select_device_name" id="select_device_name" @change="filterByAppActivityLogList()">
                <option value="">--- All Device ---</option>
                <option v-for="device,key in all_device_name" :value="device.device_name">
                      @{{  (device.device_name) }}
                </option>
              </select>

          </div>
        </div>
        <!--
        <div class="row panel panel-default" style="margin-top: 20px;" >
          <button
              type="button"
              id="delete_multiple"
              :data-id="selectedAppActLog"
              class="btn btn-danger"
              data-toggle="modal_test"
              data-backdrop='static'
              data-keyboard="false"
              data-target="#exampleModalCenter"
              @click="deleteAppActLog(selectedAppActLog)"
              style="float: right;"
          >
              <i class="fa fa-trash">&nbsp; Delete</i>
          </button>
        -->
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingAppActivityLogList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover custom-table-css" >
                    <thead>
                        <th></th>
                        <th width="20%">Action</th>
                        @php /*
                        <th>Endpoint</th>
                        <th>Endpoint Name</th>
                        */ @endphp
                        <th>IP</th>
                        <th>User</th>
                        <th>Device Type</th>
                        <th>OS / Version</th>
                        <th>
                          <a href="#" @click="sortAppActList('created_at')">
                            Datetime
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                          </a>
                        </th>
                        <th>Device</th>
                        <th>DeviceInfo</th>
                    </thead>
                    <tbody v-if="allAppActivityLog.length > 0">
                        <tr v-for="app_activity_log,key in allAppActivityLog">
                            <td><!--<input type="checkbox" v-model="selectedAppActLog" :value="app_activity_log.id">--></td>
                            <td>@{{ app_activity_log.action }}</td>
                            @php /*
                            <td>@{{ app_activity_log.apiroute }}</td>
                            <td>@{{ app_activity_log.apiroutename }}</td>
                            */ @endphp
                            <td>
                              <a  href
                                  data-toggle="modal"
                                  data-backdrop='static'
                                  data-keyboard="false"
                                  data-target="#ip_details"
                                  @click="viewIPAdressDetails(app_activity_log.ip_address)"
                              >
                                  @{{app_activity_log.ip_address}}
                              </a>
                            </td>
                            <td>@{{ app_activity_log.user.name }}</td>
                            <td>@{{ app_activity_log.device_os == 'ios' ? 'iphone' : 'android' }}</td>
                            <td>
                                @{{ app_activity_log.device_os }}
                                v @{{ app_activity_log.device_version }}
                            </td>
                            <td>@{{ app_activity_log.created_at | formatDate }}</td>
                            <td>@{{ app_activity_log.device_name }}</td>
                            <td>
                              <button
                                  type="button"
                                  id="delete_details"
                                  class="btn"

                                  alt="View Full Info"
                                  title="View Full Info"
                                  @click="viewDeviceFullInfo(app_activity_log.id)"
                              >
                                  <i class="fas fa-search"></i></i> View
                              </button>

                              <!-- Modals -->
                              <div class="modal" :id="'deviceFullInfo_' + app_activity_log.id" tabindex="-1" role="dialog">
                                  <div class="modal-dialog modal-lg" role="document">
                                      <div class="modal-content">
                                      <form class="form-horizontal was-validated" @submit.prevent="processMultipleSong" >
                                          <div class="modal-header">
                                              <h4 class="modal-title">Device Full Info - @{{ app_activity_log.apiroutename }}</h4>
                                              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                              </button>
                                          </div>
                                          <div class="modal-body vld-parent1">
                                            <div :id="app_activity_log.id">
                                              <json-tree :raw="app_activity_log.device_fullinfo"></json-tree>
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
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getAppActivityLogList()"></pagination>
            </div>
        </div>



        <!-- Modals -->
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

        <div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-trash"></i> Delete
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the app activity log?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form ref="delete_form" action="" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"> Delete </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

</admin-index-appactivitylog-component>
@stop
