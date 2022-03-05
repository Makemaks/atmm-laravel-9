@extends('layouts.layout-admin')
@section('page_heading','Subscriber List')
@section('section')



<admin-subscriber-list-component inline-template>
    <div class="col-sm-12">

        <div class="row">
            <div class="col-md-6">
              @php /*
              @include('fragments.search-list-form', ['default_field' => 'name'])
              */  @endphp
              <searched :search="search" @gosearch="getSubscriberList('search')"></searched>
            </div>
            <div class="col-md-6">

              <select v-model="select_subscription_status" name="select_subscription_status" id="select_subscription_status" @change="filterBySubscriberList()">
                <option value="">--- All Status ---</option>
                <option v-for="sub_status,key in all_subscription_status" :value="sub_status.subscription_status">
                      @{{  (sub_status.subscription_status) }}
                </option>
              </select>

            </div>
        </div>
        <div
            class="row panel panel-default"
            style="margin-top: 20px;"
        >
            <div class="panel-body" style=" padding: 0px;">
                <loading :active.sync="isLoadingSubscriberList" :can-cancel="false" :is-full-page="false"> </loading>
                <table class="table table-hover custom-table-css">
                    <caption>
                      <label style="padding:8px;">Total : @{{  total_rows }} </label>
                    </caption>
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date Signup</th>
                        <th>Last Payment </th>
                        <th>Status</th>
                        <th>Subsscription Type</th>
                        <th>Registered</th>
                        <th>Action</th>
                    </thead>
                    <tbody v-if="allSubscribers.length > 0">
                        <tr v-for="subscriber,key in allSubscribers">
                            <td>@{{subscriber.name}}</td>
                            <td>@{{subscriber.email}}</td>
                            <td>@{{subscriber.created_at}}</td>
                            <td>
                                <span v-if="subscriber.nmi_transactions.length > 0">
                                  @{{subscriber.nmi_transactions[0].date_formatted}}
                                  <button
                                    @click="getAllNMITransactionByUser(subscriber.email)"
                                    title="View All Transaction For This User"
                                    alt="View All Transaction For This User"
                                    type="button"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    class="btn btn-info btn-circle"
                                  >
                                    <i class="fa fa-search"></i>
                                  </button>
                                </span>

                                @php /*
                                @if ( count($subscriber->nmiTransactions) > 0 )
                                  {{ $subscriber->nmiTransactions[0]->date_formatted }}
                                  <button
                                    @click="getAllNMITransactionByUser('{{$subscriber->email}}')"
                                    title="View All Transaction For This User"
                                    alt="View All Transaction For This User"
                                    type="button"
                                    data-keyboard="false"
                                    data-backdrop='static'
                                    class="btn btn-info btn-circle"
                                  >
                                    <i class="fa fa-search"></i>
                                  </button>
                                @endif
                                */ @endphp
                            </td>
                            <td>
                                <span v-if="subscriber.subscription_status == 'PAID'">
                                  <span class="text-success"><b>@{{subscriber.subscription_status}}</b></span>
                                </span>
                                <span v-else>
                                  <span class="text-danger"><b>@{{subscriber.subscription_status}}</b></span>
                                </span>

                                @php
                                /****************** OLD (for Infusionsoft before) *****************/

                                /*
                                //echo $subscriber->nmiTransactions->count();
                                $subscription_status = $subscriber->subscription_status;
                                if( count($subscriber->trialLogs) > 0 ) {
                                  if( $subscriber->trialLogs[0]->still_trial == 1 ) {
                                    $subscription_status = 'TRIAL';
                                  }

                                  $number_trial_days = 14;

                                  $fromdate= strtotime($subscriber->trialLogs[0]->created_at);
                                  $todate= strtotime( date('Y-m-d h:i:s') );
                                  $calculate_seconds = $todate- $fromdate; // Number of seconds between the two dates
                                  $days = ceil($calculate_seconds / (24 * 60 * 60 )); // convert to days
                                  $trial_days_left = $number_trial_days - $days;
                                }

                                @if ( $subscription_status == 'TRIAL' )
                                    {{$subscription_status}}  ( {{$trial_days_left}} days left )
                                @else
                                    {{$subscription_status}}
                                @endif

                                */

                                /****************** for Infusionsoft before *****************/
                                @endphp
                                <span>

                                  @php
                                  /*******************
                                  //echo $subscriber->nmiTransactions->count();

                                  $trial_days_left = 0;

                                  //$number_trial_days = 14;
                                  $number_trial_days = 0;
                                  $fromdate= strtotime($subscriber->created_at);
                                  $todate= strtotime( date('Y-m-d h:i:s') );
                                  $calculate_seconds = $todate- $fromdate; // Number of seconds between the two dates
                                  $days = ceil($calculate_seconds / (24 * 60 * 60 )); // convert to days
                                  $trial_days_left = $number_trial_days - $days;
                                  *************/


                                  /******************************************************************
                                    Things need to do
                                   1. still need to add some logic here based on the latest payment date
                                   ******************************************************************/

                                  /*******************

                                  if( $trial_days_left > 0 ) {
                                      // which mean trial period is done and NMI should have already started creating a recurring payment
                                      echo 'TRIAL';
                                  } else {
                                      //echo $subscriber->subscription_status;
                                      echo ($subscriber->subscription_status == 'PAID' ? '<span class="text-success"><b>'.$subscriber->subscription_status.'</b></span>' : '<span class="text-danger"><b>'.$subscriber->subscription_status.'</b></span>');
                                  }
                                  *************/
                                  @endphp
                                </span>
                            </td>
                            <td>
                                <span v-if="subscriber.nmi_transactions.length > 0">
                                  @{{subscriber.nmi_transactions[0].order_id.split("-")[0].capitalize()}}
                                </span>
                                <span v-else>
                                </span>

                                @php
                                /*******************
                                if( $subscriber->nmiTransactions->count() > 0 ) {
                                  $orderid = explode('-',$subscriber->nmiTransactions[0]->order_id);
                                  if( count($orderid) > 1 ) {
                                    echo ucfirst($orderid[0]);
                                  }
                                }
                                else {
                                  if( $trial_days_left > 0 ) {
                                    if( $subscriber->nmiPayments->count() > 0 ) {
                                      $orderid = explode('-',$subscriber->nmiPayments[0]->orderid);
                                      if( count($orderid) > 0 ) {
                                        echo ucfirst($orderid[0]);
                                      }
                                    }
                                  }
                                }
                                *******************/
                                @endphp


                                <span v-if="subscriber.subscription_type_id == 10">
                                  Monthly
                                <span v-if="subscriber.subscription_type_id == 12">
                                  Yearly
                                </span>

                                @php
                                /*******************
                                @if ($subscriber->subscription_type_id == 10)
                                    Monthly
                                @elseif ( $subscriber->subscription_type_id == 12)
                                    Yearly
                                @endif
                                *******************/
                                @endphp
                            </td>
                            <td>
                                <span v-if="subscriber.nmi_transactions.length < 1">
                                  Infusionsoft
                                </span>
                                <span v-else>
                                  NMI
                                </span>

                                @php
                                /*******************
                                  if( $trial_days_left > 0 ) {
                                    echo 'NMI';
                                  } else {
                                    if( $subscriber->nmiTransactions->count() < 1 ) {
                                      echo 'Infusionsoft';
                                    } else {
                                      echo 'NMI';
                                    }
                                  }
                                *******************/
                                @endphp
                            </td>
                            <td>
                              <span v-if="subscriber.subscription_status == 'PAID'">
                                <button
                                    type="button"
                                    id="delete_details"
                                    :data-id="subscriber.id"
                                    class="btn btn-danger btn-circle delete_details"
                                    data-toggle="modal_test"
                                    data-backdrop='static'
                                    data-keyboard="false"
                                    data-target="#exampleModalCenter"
                                    @click="deleteSubscriber(subscriber.id)"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                              </span>
                            </td>
                        </tr>
                    </tbody>
                  </table>

            </div>
        </div>


          <div class="row">
            <div style="float:right">
                <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="getSubscriberList()"></pagination>
            </div>
          </div>


        <div class="row">
            <div style="float:right">
              @php /*
                {{ $subscribers->appends(request()->all())->links() }}
              */ @endphp
            </div>
        </div>




        <!-- Modals -->
        <div class="modal" id="showAllNMITransactionByUser" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="transform:none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle">
                            <i class="fa fa-info-circle"></i> All Transactions - @{{selected_email}}
                        </h4>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    <div class="modal-body vld-parent">
                        <loading :active.sync="isLoadingAllTransaction" :can-cancel="false" :is-full-page="false"> </loading>
                        <div style="max-height:300px; overflow:auto;">
                        <table class="table table-hover custom-table-css">
                            <thead>
                                <th>TrnsactionID</th>
                                <th>Price</th>
                                <th>Condition</th>
                                <th>Source</th>
                                <th>Success</th>
                                <th>Date</th>
                            </thead>
                            <tbody v-if="allTransactions.length > 0">
                                <tr v-for="tran,key in allTransactions">
                                    <td>@{{tran.transaction_id}}</td>
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
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        Are you sure you want to delete user and subscription?
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
</admin-subscriber-list-component>
@stop
