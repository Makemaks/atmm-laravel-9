<?php

namespace App\Http\Controllers\Web;

use App\Models\Nmipayments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nmipayments\Store;

class NmipaymentsController extends Controller
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
            $nmipayments = $this->paginate($request, [
                //'items' => (new Nmipayments()),
                'items' => (new Nmipayments())->with('user'),
                's_fields' => ['id'],
                'sortBy' => $request->sort_field,
                'sortOrder' => $request->sort_order,
            ]);
            $response = [
                 'pagination' => [
                     'total' => $nmipayments->total(),
                     'per_page' => $nmipayments->perPage(),
                     'current_page' => $nmipayments->currentPage(),
                     'last_page' => $nmipayments->lastPage(),
                     'from' => $nmipayments->firstItem(),
                     'to' => $nmipayments->lastItem()
                 ],
                 'data' => $nmipayments
             ];
             return response()->json($response);
          } else {
              return view('nmipayments.admin.index');
          }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function debug()
    {
      //return time();

      $api_method = 'POST';
      $end_point = '/api/transact.php';
      $api_data = array();
      //$api_data['amount'] = '444';
      //$api_data['ccnumber'] = '4111111111111111';
      //$api_data['ccexp'] = '1010';

      //$api_data['type'] = 'void';
      //$api_data['transactionid'] = 5428594048;
      //$api_data['void_reason'] = 'user_cancel';

      //$api_data['recurring'] = 'delete_subscription';
      //$api_data['subscription_id'] =  5429752424;


      $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);

      $data = explode("&",$nmi_data);
      for($i=0;$i<count($data);$i++) {
          $rdata = explode("=",$data[$i]);
          $responses[$rdata[0]] = $rdata[1];
      }

      /*
      if(strtolower($responses['responsetext']) == 'success') {
          $nmi = new Nmipayments();
          $nmi->response = $responses['response'];
          $nmi->transactionid = $responses['transactionid'];
          $nmi->responsetext = $responses['responsetext'];
          $nmi->orderid = $responses['orderid'];
          $nmi->response_code = $responses['response_code'];
          $nmi->raw_data = $nmi_data;
          $nmi->save();
      }
      */


      return $responses;


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
    public function store(Store $request)
    {

      $user = User::where('email', '=', $request->input('email') )->first();
      if($user) {
          //return response()->json( ['error' => true, 'message' => 'Email already exist.' ], 403 );
          $errors = new \stdClass;
          $errors->email = 'Email already exist.';
          return response()->json( ['errors' => $errors, 'message' => 'The given data was invalid.' ], 422 );
      }

      $periodic = trim( $request->input('periodic') );
      if ($periodic != 'monthly' && $periodic != 'yearly')   { // need to validate in case data are tampered
          //return response()->json( ['error' => true, 'message' => 'Please select a subscription plan.' ], 403 );
          $errors = new \stdClass;
          $errors->periodic = 'Please select a subscription plan.';
          return response()->json( ['errors' => $errors, 'message' => 'The given data was invalid.' ], 422 );
      }

      $nmi = new Nmipayments();
      $nmi_api_data = $nmi->getAPIData($request);
      //return response()->json( ['nmi_data' => $nmi_api_data ], 422 );

      $api_method = 'POST';
      $end_point = '/api/transact.php';
      $api_data = $nmi_api_data;
      $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);

      $data = explode("&",$nmi_data);
      for($i=0;$i<count($data);$i++) {
          $rdata = explode("=",$data[$i]);
          $responses[$rdata[0]] = $rdata[1];
      }

      //return response()->json( ['nmi_data' => $responses ], 422 );

      $error_msg = '';
      $error = true;
      $status_code = 403;
      if(strtolower($responses['responsetext']) == 'success' || strtolower($responses['responsetext']) == 'approved' || strtolower($responses['responsetext']) == 'subscription added') {
          $user = new User();
          $user->name = trim( $request->input('fname') ).' '.trim( $request->input('lname') );
          $user->email = trim( $request->input('email') );
          $user->password = bcrypt( trim( $request->input('password') ) );
          $user->subscription_status = 'PAID';
          $user->save();

          $nmi = new Nmipayments();
          $nmi->response = $responses['response'];
          $nmi->transactionid = $responses['transactionid'];
          $nmi->responsetext = $responses['responsetext'];
          $nmi->orderid = $responses['orderid'];
          $nmi->response_code = $responses['response_code'];
          $nmi->raw_data = $nmi_data;
          $nmi->email = trim( $request->input('email') );
          $nmi->user_id = $user->id;
          $nmi->subscription_id = $responses['subscription_id'];
          $nmi->status = 'Active';
          $nmi->plan_id = (int) $responses['plan_id'];
          $nmi->save();

          $error = false;
          $status_code = 200;
          $error_msg = 'Thank you for subscribing to All Things Michael McLean';

          //$this->notifyAdminSubsriber($request);

          Auth::loginUsingId($user->id);
          $user = Auth::user();
          Session::put('user', $user);
          Session::put('new_subscriber_user', 1); // for FB pixel Subscribe

          $this->get_latest_data_from_nmi();

      } else {
          if( isset($responses['responsetext']) && trim($responses['responsetext']) != '' ) {
            $error_msg = $responses['responsetext'];
          } else {
            $error_msg = 'Problem occured when submitting a payment in NMI.';
          }

          $status_code = 422;
          $errors = new \stdClass;
          $errors->nmimessage = $error_msg;
          return response()->json( ['errors' => $errors, 'message' => 'The given data was invalid.' ], 422 );
      }

      return response()->json( ['error' => $error, 'message' => $error_msg ], $status_code );
    }

    /**
     * Send Request to ATMM api
     * Reference

     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function get_latest_data_from_nmi () {
        try {
          // for local and dev server
          $userapi_data = array();
          $userapi_data['email'] = 'devapi@songwriter.com';
          $userapi_data['password'] = 'tc123456';
          $userapi_data = json_encode($userapi_data);
          $url = config('nmi.base_url').'/api/login';
          $options = [
              'headers' => [
                  'Content-Type' => 'application/json',
              ],
              'body' => $userapi_data,
          ];
          $client = new \GuzzleHttp\Client();
          $response = $client->request('POST', $url, $options);
          $sss_api = json_decode($response->getBody());
          $sss_api_token = $sss_api->token;

          $url = config('nmi.base_url').'/api/nmitransactions';
          $options = [
              'headers' => [
                  'Content-Type' => 'application/json',
                  'Authorization'=> 'Bearer '.$sss_api_token
              ]
          ];
          $client = new \GuzzleHttp\Client();
          $response = $client->request('GET', $url, $options);

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Nmipayments  $nmipayments
     * @return \Illuminate\Http\Response
     */
    public function show($transactionid)
    {
        $api_method = 'POST';
        $end_point = '/api/query.php';
        $api_data = array();
        $api_data['transaction_id'] = $transactionid;
        $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);
        $nmi_data = json_encode($nmi_data);
        return $nmi_data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nmipayments  $nmipayments
     * @return \Illuminate\Http\Response
     */
    public function edit(Nmipayments $nmipayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nmipayments  $nmipayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nmipayments $nmipayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nmipayments  $nmipayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nmipayments $nmipayments)
    {
        //
    }
}
