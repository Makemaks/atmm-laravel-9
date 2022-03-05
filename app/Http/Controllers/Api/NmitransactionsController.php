<?php

namespace App\Http\Controllers\Api;

use App\Models\Nmitransactions;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Batch;

class NmitransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        /*
        https://merchantsolutionservices.transactiongateway.com/api/query.php?security_key=4S6TM9GkMPEfPS7n9R9GsBb7g7RQw628&transaction_id=5490211911
        https://merchantsolutionservices.transactiongateway.com/api/query.php?security_key=4S6TM9GkMPEfPS7n9R9GsBb7g7RQw628&transaction_type=cc&first_name=James&last_name=Bolongan&source=recurring
        https://merchantsolutionservices.transactiongateway.com/api/query.php?security_key=4S6TM9GkMPEfPS7n9R9GsBb7g7RQw628&transaction_id=5474888844
        */

        /******************************************************
          get first the latest data from NMI API and
          then do a bulk insert or update to our DB
        ******************************************************/

        $api_method = 'POST';
        $end_point = '/api/query.php';
        $api_data = array();
        //$api_data['source'] = 'recurring';
        $api_data[] = '';
        $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);

        if( count($nmi_data) > 0 ) {
          $all_transaction_insert = [];
          $all_transaction_update = [];
          foreach($nmi_data as $ind => $tran) {
              $checktransaction = Nmitransactions::select('id')
                                                ->where('transaction_id','=',$tran->transaction_id)
                                                ->first();
              if($checktransaction) { // will update only our existing data
                $nmi_transaction = new Nmitransactions();
                $nmi_transaction->id = $checktransaction->id;
                $nmi_transaction->transaction_id = $tran->transaction_id;
                $nmi_transaction->transaction_type = $tran->transaction_type;
                $nmi_transaction->condition = $tran->condition;
                $nmi_transaction->order_id = $tran->order_id;
                $nmi_transaction->ponumber  = $tran->ponumber;
                $nmi_transaction->first_name = $tran->first_name;
                $nmi_transaction->last_name = $tran->last_name;
                $nmi_transaction->address_1 = $tran->address_1;
                $nmi_transaction->city = $tran->city;
                $nmi_transaction->state = $tran->state;
                $nmi_transaction->postal_code = $tran->postal_code;
                $nmi_transaction->country = $tran->country;
                $nmi_transaction->email = $tran->email;
                $nmi_transaction->phone = $tran->phone;
                $nmi_transaction->amount  = $tran->action->amount;
                $nmi_transaction->action_type  = $tran->action->action_type;
                $nmi_transaction->date  = $tran->action->date;
                $formatted_date = '';
                $tran_date = (string) $tran->action->date;
                for($i =0;$i < strlen($tran_date);$i++) {
                  $formatted_date .= $tran_date[$i];
                  if($i == 3 || $i == 5) {
                    $formatted_date .= '-';
                  }
                  if($i == 7) {
                    $formatted_date .= ' ';
                  }
                  if($i == 9 || $i == 11) {
                    $formatted_date .= ':';
                  }
                }
                $nmi_transaction->original_transaction_id  = $tran->original_transaction_id;
                $nmi_transaction->success  = $tran->action->success;
                $nmi_transaction->source  = $tran->action->source;
                $nmi_transaction->username  = $tran->action->username;
                $nmi_transaction->response_text  = $tran->action->response_text;
                $nmi_transaction->response_code  = $tran->action->response_code;
                $nmi_transaction->processor_response_text  = $tran->action->processor_response_text;
                $nmi_transaction->processor_response_code  = $tran->action->processor_response_code;
                $nmi_transaction->requested_amount  = $tran->action->requested_amount;
                $nmi_transaction->processor_response_text  = $tran->action->processor_response_text;
                $all_transaction_update[] = $nmi_transaction->attributesToArray();
              } else {
                $nmi_transaction = new Nmitransactions();
                $nmi_transaction->transaction_id = $tran->transaction_id;
                $nmi_transaction->transaction_type = $tran->transaction_type;
                $nmi_transaction->condition = $tran->condition;
                $nmi_transaction->order_id = $tran->order_id;
                $nmi_transaction->ponumber  = $tran->ponumber;
                $nmi_transaction->first_name = $tran->first_name;
                $nmi_transaction->last_name = $tran->last_name;
                $nmi_transaction->address_1 = $tran->address_1;
                $nmi_transaction->city = $tran->city;
                $nmi_transaction->state = $tran->state;
                $nmi_transaction->postal_code = $tran->postal_code;
                $nmi_transaction->country = $tran->country;
                $nmi_transaction->email = $tran->email;
                $nmi_transaction->phone = $tran->phone;
                $nmi_transaction->amount  = $tran->action->amount;
                $nmi_transaction->action_type  = $tran->action->action_type;
                $nmi_transaction->date  = $tran->action->date;
                $formatted_date = '';
                $tran_date = (string) $tran->action->date;
                for($i =0;$i < strlen($tran_date);$i++) {
                  $formatted_date .= $tran_date[$i];
                  if($i == 3 || $i == 5) {
                    $formatted_date .= '-';
                  }
                  if($i == 7) {
                    $formatted_date .= ' ';
                  }
                  if($i == 9 || $i == 11) {
                    $formatted_date .= ':';
                  }
                }
                $nmi_transaction->original_transaction_id  = $tran->original_transaction_id;
                $nmi_transaction->date_formatted  = $formatted_date;
                $nmi_transaction->success  = $tran->action->success;
                $nmi_transaction->source  = $tran->action->source;
                $nmi_transaction->username  = $tran->action->username;
                $nmi_transaction->response_text  = $tran->action->response_text;
                $nmi_transaction->response_code  = $tran->action->response_code;
                $nmi_transaction->processor_response_text  = $tran->action->processor_response_text;
                $nmi_transaction->processor_response_code  = $tran->action->processor_response_code;
                $nmi_transaction->requested_amount  = $tran->action->requested_amount;
                $nmi_transaction->processor_response_text  = $tran->action->processor_response_text;
                $all_transaction_insert[] = $nmi_transaction->attributesToArray();
             }
          }
          if( count($all_transaction_insert) > 0 ) {
            Nmitransactions::insert($all_transaction_insert);  // bulk insert
          }
          if( count($all_transaction_update) > 0 ) { // bulk update
            Batch::update(new Nmitransactions, $all_transaction_update, 'id');
          }
        }

        /***************************************************************/

        if( !$request->sort_order ) {
          $request->sort_order = 'desc';
        }
        if( $request->sort_field == 'created_at' ) {
          $request->sort_field = 'id';
        }

        $nmitransactions = $this->paginate($request, [
            //'items' => (new Nmitransactions()),
            'items' => (new Nmitransactions())->with('user'),
            's_fields' => ['id'],
            'sortBy' => $request->sort_field,
            'sortOrder' => $request->sort_order,
        ]);
        $response = [
             'pagination' => [
                 'total' => $nmitransactions->total(),
                 'per_page' => $nmitransactions->perPage(),
                 'current_page' => $nmitransactions->currentPage(),
                 'last_page' => $nmitransactions->lastPage(),
                 'from' => $nmitransactions->firstItem(),
                 'to' => $nmitransactions->lastItem()
             ],
             'data' => $nmitransactions
         ];
         return response()->json($response);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nmitransactions  $nmitransactions
     * @return \Illuminate\Http\Response
     */
    public function show(Nmitransactions $nmitransactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nmitransactions  $nmitransactions
     * @return \Illuminate\Http\Response
     */
    public function edit(Nmitransactions $nmitransactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nmitransactions  $nmitransactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nmitransactions $nmitransactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nmitransactions  $nmitransactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nmitransactions $nmitransactions)
    {
        //
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserStatus(Request $request)
    {
      //$users = User::orderBy('updated_at', 'asc')->limit(5)->get();
      $users = User::where('isAdmin', 0)
                    ->where('email', '<>','devapi@songwriter.com')
                    ->where('email', '<>','user@songwriter.com')
                    ->where('email', '<>','jamesb@songwriter.com')
                    ->where('email', '<>','google@songwriter.com')
                    ->where('email', '<>','apple@songwriter.com')
                    ->where('subscription_status', '<>','FORCE_STOP')
                    ->orderBy('updated_at', 'asc')
                    ->limit(5)
                    ->get();
      if($users) {
        foreach ($users as $key => $u) {
            $user = User::findOrFail($u->id);
            if( $user ) {
              $nmitransactions = Nmitransactions::select('transaction_id', 'email',
                                                       'source', 'success', 'condition',
                                                       'date', 'date_formatted')
                                ->where('email', $user->email)
                                ->orderBy('id', 'desc')->first();
              if($nmitransactions) {
                  if($nmitransactions->success == 0 || $nmitransactions->condition == 'failed') {
                    $user->subscription_status = 'INACTIVE';
                  } else {

                      $current_date = date('Y-m-d');
                      $date=date_create($current_date);
                      date_sub($date,date_interval_create_from_date_string("2 months"));
                      $result = date_format($date,"Y-m-d");

                      if ( strtotime($nmitransactions->date_formatted) < strtotime($result)) {
                        $user->subscription_status = 'INACTIVE';
                      } else {
                        $user->subscription_status = 'PAID';
                      }
                  }
              } else {
                $user->subscription_status = 'INACTIVE';
              }

            }

            $user->updated_at = date('Y-m-d G:i:s');
            $user->is_nmi_checked = 1;
            $user->save();

        }

      }

      return $users;

    }

}
