@extends('layouts.layout-admin')
@section('page_heading','NMI Transactions')
@section('section')
<admin-index-tansaction-component inline-template>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-md-6">
                <searched :search="search" @gosearch="getNMITransactionList('search')"></searched>
            </div>

            <div class="col-md-6">
                <select v-model="select_source" name="select_source" id="select_source" @change="filterByNMITransactionList()">
                  <option value="">--- All Sources ---</option>
                  <option v-for="src,key in sources" :value="src.source">
                        @{{  capitalize(src.source) }}
                  </option>
                </select>

              <select v-model="select_condition" name="select_condition" id="select_condition" @change="filterByNMITransactionList()">
                  <option value="">--- All Conditions ---</option>
                  <option v-for="src,key in conditions" :value="src.condition">
                      @{{  capitalize(src.condition) }}
                  </option>
              </select>
            </div>

        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
              <loading :active.sync="isLoadingTransactionList" :can-cancel="false" :is-full-page="false"> </loading>
              <table class="table table-hover custom-table-css">
                  <thead>
                      <th><input name="" type="checkbox"></th>
                      <th>
                        <a href="#" @click="sortTransactionList('transaction_id')">
                            TrnsactionID
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortTransactionList('first_name')">
                            Name
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortTransactionList('email')">
                            Email
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortTransactionList('amount')">
                            Price
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortTransactionList('condition')">
                            Condition
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>
                        <a href="#" @click="sortTransactionList('source')">
                            Source
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th>Success</th>
                      <th>
                        <a href="#" @click="sortTransactionList('date')">
                            Date
                            <i v-if="sort_order == 'asc'" class="fas fa-chevron-down"></i>
                            <i v-if="sort_order == 'desc'" class="fas fa-chevron-up"></i>
                        </a>
                      </th>
                      <th></th>
                  </thead>
                  <tbody v-if="allTransactions.length > 0">
                      <tr v-for="tran,key in allTransactions">
                          <td><input type="checkbox" v-model="tran.checked" :checked="tran.checked"></td>
                          <td>@{{tran.transaction_id}}</td>
                          <td>@{{tran.first_name}} @{{tran.last_name}}</td>
                          <td>@{{tran.email}}</td>
                          <td>@{{tran.amount}}</td>
                          <td>@{{tran.condition}}</td>
                          <td>@{{tran.source}}</td>
                          <td>
                            <span v-if="tran.success == 1">
                              <i class="fa fa-check fa-fw text-success"></i>
                            </span>
                            <span v-if="tran.success == 0">
                              <i class="fa fa-close fa-fw text-danger"></i>
                            </span>
                          </td>
                          <td>@{{tran.date_formatted}}</td>
                          <td>
                            <button
                                title="View Full Info"
                                alt="View Full Info"
                                type="button"
                                :data-id="tran.id"
                                class="btn btn-success btn-circle delete_details"
                                data-toggle="modal_test"
                                data-backdrop='static'
                                data-keyboard="false"
                                @click="getNMIFullTransactionInfo(tran.id)"
                            >
                                <i class="fa fa-search"></i>
                            </button>
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
        </div>


        <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getNMITransactionList()"></pagination>
            </div>
        </div>

        <div class="modal" id="showFullTransaction" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="transform:none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-trash"></i> Transaction
                        </h4>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    <div class="modal-body vld-parent">
                        <loading :active.sync="isLoadingTransactionInfo" :can-cancel="false" :is-full-page="false"> </loading>
                        <div>
                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Transaction ID</label>
                                  <div class="col-sm-3"> @{{transactionInfo.transaction_id}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Transaction Type</label>
                                  <div class="col-sm-3"> @{{transactionInfo.transaction_type}} </div>
                              </div>
                            </div>
                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Condition</label>
                                  <div class="col-sm-3"> @{{transactionInfo.condition}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Order ID</label>
                                  <div class="col-sm-3"> @{{transactionInfo.order_id}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Order Description</label>
                                  <div class="col-sm-3"> @{{transactionInfo.order_description}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Purchase Order Number</label>
                                  <div class="col-sm-3"> @{{transactionInfo.ponumber}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Fullname</label>
                                  <div class="col-sm-3"> @{{transactionInfo.first_name}} @{{transactionInfo.last_name}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Email</label>
                                  <div class="col-sm-3"> @{{transactionInfo.email}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Address</label>
                                  <div class="col-sm-3"> @{{transactionInfo.address_1}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">City</label>
                                  <div class="col-sm-3"> @{{transactionInfo.city}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">State</label>
                                  <div class="col-sm-3"> @{{transactionInfo.state}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Postal Code</label>
                                  <div class="col-sm-3"> @{{transactionInfo.postal_code}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Country</label>
                                  <div class="col-sm-3"> @{{transactionInfo.country}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Phone</label>
                                  <div class="col-sm-3"> @{{transactionInfo.phone}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Card Number</label>
                                  <div class="col-sm-3"> @{{transactionInfo.cc_number}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Card Exp</label>
                                  <div class="col-sm-3"> @{{transactionInfo.cc_exp}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Card Type</label>
                                  <div class="col-sm-3"> @{{transactionInfo.cc_type}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Processor ID</label>
                                  <div class="col-sm-3"> @{{transactionInfo.processor_id}} </div>
                              </div>
                            </div>

                            @php /*
                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Amount</label>
                                  <div class="col-sm-3"> @{{transactionInfoAction.amount}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">Type</label>
                                  <div class="col-sm-3"> @{{transactionInfoAction.action_type}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Date</label>
                                  <div class="col-sm-3"> @{{transactionInfoAction.date}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">IP Address</label>
                                  <div class="col-sm-3"> @{{transactionInfoAction.ip_address}} </div>
                              </div>
                            </div>

                            <div class="row form-group">
                              <div>
                                  <label class="col-sm-3 control-label">Source</label>
                                  <div class="col-sm-3"> @{{transactionInfoAction.source}} </div>
                              </div>
                              <div>
                                  <label class="col-sm-3 control-label">API Method</label>
                                  <div class="col-sm-3"> @{{transactionInfoAction.api_method}} </div>
                              </div>
                            </div>
                            */ @endphp

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</admin-index-tansaction-component>
@stop
