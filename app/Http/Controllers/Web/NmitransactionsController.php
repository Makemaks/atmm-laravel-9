<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Nmitransactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

      try {
        if(!$this->isCurrentUserAdmin())
          abort(404);

        if($request->ajax()){
          if( !$request->sort_order ) {
            $request->sort_order = 'desc';
          }
          if( $request->sort_field == 'created_at' ) {
            $request->sort_field = 'id';
          }

          $sources = Nmitransactions::distinct()->get(['source']);
          $condition = Nmitransactions::distinct()->get(['condition']);

          $filter_by_fields = array();
          if( $request->select_source != '' )
            $filter_by_fields['source'] = trim($request->select_source);
          if( $request->select_condition != '' )
            $filter_by_fields['condition'] = $request->select_condition;

          $nmitransactions = $this->paginate($request, [
              //'items' => (new Nmitransactions()),
              'items' => (new Nmitransactions())->with('user'),
              //'s_fields' => ['email', 'condition', 'source', 'first_name', 'transaction_id'],
              's_fields' => ['first_name'],
              'sortBy' => $request->sort_field,
              'sortOrder' => $request->sort_order,
              'filter_by_fields' => $filter_by_fields,
          ]);

          //return $nmitransactions;

          $response = [
               'pagination' => [
                   'total' => $nmitransactions->total(),
                   'per_page' => $nmitransactions->perPage(),
                   'current_page' => $nmitransactions->currentPage(),
                   'last_page' => $nmitransactions->lastPage(),
                   'from' => $nmitransactions->firstItem(),
                   'to' => $nmitransactions->lastItem()
               ],
               'data' => $nmitransactions,
               'sources' => $sources,
               'conditions' => $condition
           ];
           return response()->json($response);
        } else {
            return view('nmitransactions.admin.index');
        }



      } catch (\Throwable $th) {
          throw $th;
      }

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $nmi_transaction = Nmitransactions::findOrFail($id);
        // fetch other info from the API
        $api_method = 'POST';
        $end_point = '/api/query.php';
        $api_data = array();
        $api_data['transaction_id'] = $nmi_transaction->transaction_id;
        $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);
        //return $nmi_data;

        // save other info in the DB
        foreach($nmi_data as $ind => $tran) {
          $nmi_transaction->order_description = $tran->order_description;
          $nmi_transaction->cc_number = $tran->cc_number;
          $nmi_transaction->cc_hash = $tran->cc_hash;
          $nmi_transaction->cc_exp = $tran->cc_exp;
          $nmi_transaction->cc_type = $tran->cc_type;
          $nmi_transaction->processor_id = $tran->processor_id;
          $nmi_transaction->save();
        }

        return Nmitransactions::findOrFail($id);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionByUser(Request $request)
    {

        if(!$this->isCurrentUserAdmin())
          return response()->json('error', 404);

        if($request->ajax()){
          $nmitransactions = Nmitransactions::select('transaction_id', 'order_id', 'email',
                                                     'original_transaction_id', 'source', 'success', 'condition',
                                                     'date', 'date_formatted', 'amount', 'first_name', 'last_name')
                              ->where('email', $request->email)
                              ->orderBy('transaction_id', 'desc')->get();
           $data = array();
           $data['items'] = $nmitransactions;
           $data['email'] = $request->email;
           return $data;
        } else {
           return response()->json('error', 401);
        }

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserDontHaveRecurring(Request $request)
    {

        if(!$this->isCurrentUserAdmin())
          return response()->json('error', 404);

          // get all the email from nmitransactions table that dont have recurring
          $sql = ' SELECT DISTINCT(email) FROM nmitransactions
                      WHERE email <> "" AND email NOT IN
                        (SELECT DISTINCT(email) FROM nmitransactions WHERE source="recurring")
                  ';
          $nmitransactions = DB::select(DB::raw($sql));

          if( count($nmitransactions) > 0 ) {
              // get the date signup from users table
              foreach ($nmitransactions as $key => $nmitransaction) {
                echo $nmitransaction->email;
                //$user = User::select('created_at')->where('email', $nmitransaction->email)->first();
                $user = User::where('email', $nmitransaction->email)->first();
                if($user) {

                  //$full_name = explode(' ', $user->name) ;
                  //$request['fname'] = $full_name[0];
                  //$request['lname'] = $full_name[1];
                  echo $user;
                  echo $user->created_at;
                }
                echo '<br>';
              }
          }
    }


}
