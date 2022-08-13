@extends('layouts.layout-admin')
@section('page_heading','App Login History')
@section('style')
  <style>
    .closebtn {
      margin-left: 18px;
      font-weight: bold;
      font-size: 22px;
      line-height: 20px;
      cursor: pointer;
      transition: 0.3s;
    }
    .alert {
      width: 718px;
    }
  </style>
@endsection
@section('section')
<admin-index-apploginhistory-component inline-template>
    <div class="col-sm-12">
      @if (Session::has('messageAppLogError'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i>
          &nbsp; {{ Session::get('messageAppLogError') }}
          <span class="closebtn" style="color: red;" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
      @endif
      @if (Session::has('messageAppLogSuccess'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i>
          &nbsp; {{ Session::get('messageAppLogSuccess') }}
          <span class="closebtn" style="color: green;" onclick="this.parentElement.style.display='none';">&times;</span>
        </div>
      @endif
        <div class="row">
            <div class="col-md-6">
                    <searched :search="search" @gosearch="getAppLoginHistoryList('search')"></searched>
            </div>
            <div class="col-md-6">
                <select v-model="select_appLogIn_user" name="select_appLogIn_user" id="select_appLogIn_user" @change="filterByAppLoginHistoryList()">
                  <option value="">--- All User ---</option>
                  <option v-for="user,key in all_appLogIn_user" :value="user.user_id">
                        @{{  (user.user.name) }}
                  </option>
                </select>

                <select v-model="select_device_os" name="select_device_os" id="select_device_os" @change="filterByAppLoginHistoryList()">
                  <option value="">--- All OS ---</option>
                  <option v-for="os,key in all_device_os" :value="os.device_os">
                        @{{  (os.device_os) }}
                  </option>
                </select>

                <select v-model="select_device_version" name="select_device_version" id="select_device_version" @change="filterByAppLoginHistoryList()">
                  <option value="">--- All Version ---</option>
                  <option v-for="device,key in all_device_version" :value="device.device_version">
                        Version: @{{  (device.device_version) }}
                  </option>
                </select>

            </div>
        </div>
        <div class="row panel panel-default" style="margin-top: 20px;" >
          <button
              type="button"
              id="delete_multiple"
              :data-id="selectedAppLogInHistory"
              class="btn btn-danger"
              data-toggle="modal_test"
              data-backdrop='static'
              data-keyboard="false"
              data-target="#exampleModalCenter"
              @click="deleteAppLogIn(selectedAppLogInHistory)"
              style="float: right;"
          >
              <i class="fa fa-trash">&nbsp; Delete</i>
          </button>
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingAppLoginHistoryList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover custom-table-css" >
                    <thead>
                        <th></th>
                        <th>USER</th>
                        <th>OS</th>
                        <th>VERSION</th>
                        <th>
                          <a href="#" @click="sortAppLogInList('created_at')">
                            DATE TIME
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                          </a>
                        </th>
                        <th>DEVICE FULL INFO</th>
                    </thead>
                    <tbody v-if="allAppLoginHistory.length > 0">
                        <tr v-for="login_history,key in allAppLoginHistory">
                            <td><input type="checkbox" v-model="selectedAppLogInHistory" :value="login_history.id"></td>
                            <td>@{{ login_history.user.name }}</td>
                            <td>@{{ login_history.device_os }}</td>
                            <td>@{{ login_history.device_version }}</td>
                            <td>@{{ login_history.created_at | formatDate }}</td>
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

        <!--Modal-->
        <div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-trash"></i> Delete
                        </h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the app log in history?
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

</admin-index-apploginhistory-component>
@stop
