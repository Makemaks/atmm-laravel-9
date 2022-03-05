<?php

namespace App\Http\Controllers;

use App\Models\Appactivitylog;
use App\Models\Expo;
use App\Models\InfusionsoftToken;
use App\Models\InfusionsoftLog;
use App\Models\InfusionsoftSettings;
use App\Models\Nmipayments;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Batch;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function paginate(Request $request, $options) {

        extract($options);
        if($request->has('search')) {
            foreach($s_fields as $idx => $field) {
                if($idx === 0) {
                    $items = $items->where($field, 'like', '%'. $request->get('search') .'%');
                } else {
                    $items = $items->orWhere($field, 'like', '%'. $request->get('search') .'%');
                }
            }
        }

        if ( !empty($options['filter_by_fields']) ) {
            foreach($filter_by_fields as $field => $value) {
              if($value != '') {
                $items = $items->where($field, $value);
              }
            }
        }

        //return $request;
        //return $request->has('sortBy');
        $sortOrder = strtolower(($request->input('sortOrder')));
        $sortOrder = ($sortOrder === 'desc' ? 'desc' : 'asc');
        //return $sortOrder;
        if ($request->has('sortBy')) {
            $items = $items->orderBy($request->input('sortBy'), $sortOrder);
            $items = $items->orderBy('id', 'desc');
        } else {
            if (!empty($options['sortBy']) && !empty($options['sortOrder'])) {
                $items = $items->orderBy($options['sortBy'], $options['sortOrder']);
            }
        }

        $perPage = 10;

        if($request->has('perPage')) {
            if(is_numeric($request->input('perPage'))) {
                $perPage = $request->input('perPage');
            }
        }

        return $items->paginate($perPage);
    }

    function isCurrentUserAdmin() {
        $user = Session::get('user');
        if($user->isAdmin)
            return true;
        else
            return false;
    }

    /**
     * Send notification to Expo Servers
     * Reference: https://docs.expo.io/versions/latest/guides/push-notifications/
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMobileNotification($category, $title)
    {
        $result = array();
        $expos = Expo::select('value')->get();
        if( count($expos) > 0 ) {
          $api_data = array();
          foreach( $expos as $key => $expo) {
            $api_data = array();
            $api_data['to'] = $expo->value;
            $api_data['title'] = 'New All Things Michael Mclean '.$category.' Release: '.$title.'';
            $api_data['body'] = 'New All Things Michael Mclean '.$category.' Release: '.$title.'';
            $api_data = json_encode($api_data);
            $url = 'https://exp.host/--/api/v2/push/send';
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => $api_data,
            ];
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $url, $options);
            $result[$expo->value] = json_decode($response->getBody());
          }
        }
        return $result;
    }

    /**
     * Send Request to Infusionsoft API
     * Reference
     *  REST API : https://developer.infusionsoft.com/docs/rest/
     *  XML-RPC API : https://developer.infusionsoft.com/docs/xml-rpc/

     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function api_send_request_to_infusion_soft ($api_method, $end_point, $api_protocol='', $api_data='') {
        try {
            if(app()->environment('production'))
                $this->api_refresh_access_token_infusionsoft();

            $_data = InfusionsoftToken::orderBy('id', 'desc')->first();
            if(app()->environment('production')) {
                $_data_access_token = $_data->access_token;
            } else {
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

                $url = config('nmi.base_url').'/api/get-infusionsoft-token';
                $options = [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization'=> 'Bearer '.$sss_api_token
                    ]
                ];
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', $url, $options);
                $sss_inf = json_decode($response->getBody());
                $_data_access_token = $sss_inf->infusionsoft_token;
            }

            $inf_token = new InfusionsoftToken();
            if( trim($api_protocol) == '' ) {
                $url = $inf_token->getApiEndPoint().$end_point;
                $options = [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization'=> 'Bearer '.$_data_access_token
                    ]
                ];
                if( trim($api_data) != '' ) {
                  $options['body'] = $api_data;
                }
            } else {
                $url = $inf_token->getApiEndPoint($api_protocol).'?access_token='.$_data_access_token;
                $api_data = str_replace('infusionsoft_token_value_here', $_data_access_token, $api_data);
                $options = [
                    'headers' => [
                        'Content-Type' => 'text/xml; charset=UTF8',
                    ],
                    'body' => $api_data,
                ];
            }

            $client = new \GuzzleHttp\Client();
            $response = $client->request($api_method, $url, $options);

            if( trim($api_protocol) == '' )
                return json_decode($response->getBody());
            else
                return new \SimpleXMLElement($response->getBody()->getContents());

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function api_refresh_access_token_infusionsoft () {
        try {
            $_db_data = InfusionsoftToken::orderBy('id', 'desc')->first();
            $refresh_token = $_db_data->refresh_token;

            $inf_token = new InfusionsoftToken();
            $sh = '
                curl -X "POST" "'.$inf_token->getEndPoint().'token" \
                -H \'Content-Type: application/x-www-form-urlencoded; charset=utf-8\' \
                 -u \''.$inf_token->getClientId().':'.$inf_token->getClientSecret().'\' \
                 --data-urlencode "refresh_token='.$refresh_token.'" \
                 --data-urlencode "grant_type=refresh_token"
                ';
            $sh_result = shell_exec( $sh );
            $_data = json_decode($sh_result);
            if($sh_result) {
                $inftoken = new InfusionsoftToken();
                $inftoken->access_token = $_data->access_token;
                $inftoken->refresh_token = $_data->refresh_token;
                $inftoken->token_type = $_data->token_type;
                $inftoken->expires_in = $_data->expires_in;
                $inftoken->scope = $_data->scope;
                $inftoken->save();
                DB::statement('call RemoveInfusionSoftOldTokens()');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getInfusionsoftProducts() {
      $api_method = 'GET';
      $end_point = 'products';
      $infusionsoft_data = $this->api_send_request_to_infusion_soft($api_method, $end_point);
      $inf_data = $this->extractData($infusionsoft_data->products);
      $response = [
           'data' => $inf_data
       ];
      return $response;
    }

    public function extractData($products) {
        if( count($products) > 0 ) {

            $all_inf_products_insert = [];
            $all_inf_products_update = [];
            foreach($products as $ind => $product) {
                if (isset($product->subscription_plans) && count($product->subscription_plans) > 0) {
                    foreach ($product->subscription_plans as $subscription_plan) {
                        $checkproduct = InfusionsoftSettings::select('id')
                                                          ->where('product_id','=',$product->id)
                                                          ->where('subscription_plan_id','=',$subscription_plan->id)
                                                          ->first();
                        if($checkproduct) { // will update only our existing data
                          $infsettings = new InfusionsoftSettings();
                          $infsettings->id = $checkproduct->id;
                          $infsettings->product_id = $product->id;
                          $infsettings->subscription_plan_id = $subscription_plan->id;
                          $infsettings->isactive = (int) $subscription_plan->active;
                          $all_inf_products_update[] = $infsettings->attributesToArray();
                        } else {
                          $infsettings = new InfusionsoftSettings();
                          $infsettings->product_id = $product->id;
                          $infsettings->subscription_plan_id = $subscription_plan->id;
                          $infsettings->isactive = (int) $subscription_plan->active;
                          $all_inf_products_insert[] = $infsettings->attributesToArray();
                       }
                    }
                }
            }

            if( count($all_inf_products_insert) > 0 ) {
              InfusionsoftSettings::insert($all_inf_products_insert);  // bulk insert
            }
            if( count($all_inf_products_update) > 0 ) { // bulk update
              Batch::update(new InfusionsoftSettings, $all_inf_products_update, 'id');
            }


            /** For including some data from DB */
            foreach($products as $ind => $product) {
                if (isset($product->subscription_plans) && count($product->subscription_plans) > 0) {
                    foreach ($product->subscription_plans as $subscription_plan) {
                      $checkproduct = InfusionsoftSettings::select('id','is_hide','sales_tax')
                                                        ->where('product_id','=',$product->id)
                                                        ->where('subscription_plan_id','=',$subscription_plan->id)
                                                        ->first();
                      $subscription_plan->is_hide = $checkproduct->is_hide;
                      $subscription_plan->sales_tax = $checkproduct->sales_tax;
                      $subscription_plan->db_id = $checkproduct->id;

                      $product_price = $subscription_plan->plan_price -  $checkproduct->sales_tax;
                      $product_price = round($product_price,2);
                      $subscription_plan->product_price = $product_price;
                    }
                }
            }

        }
        return $products;
    }


    /**
     * Send Request to NMI API
     * Reference
     * Url : https://www.nmi.com/
     * API : https://merchantsolutionservices.transactiongateway.com/merchants/resources/integration/integration_portal.php?

     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function api_send_request_to_nmi ($api_method, $end_point, $api_data) {
        try {
            $nmi = new Nmipayments();
            $api_data['security_key'] = $nmi->getAccesToken();
            $url = $nmi->getApiEndPoint().$end_point;

            $options['form_params'] = $api_data;

            $client = new \GuzzleHttp\Client();
            $response = $client->request($api_method, $url, $options);

            if ( $end_point == '/api/query.php' )
              return new \SimpleXMLElement($response->getBody());
            else
              return $response->getBody();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function get_device_name (Request $request) {
      $device_name = '(other android model)';
      $device_details = strtolower($request->header('device-details'));
      $devices = array('samsung', 'huawei', 'oppo', 'realme', 'vivo', 'poco', 'xiaomi', 'infinix', 'techno', 'toshiba',
                       'acer', 'allview', 'archos', 'benq', 'bird', 'oneplus', 'motorola','t-mobile', 'google', 'lge',
                       'ios');
      if( count($devices) > 0 ) {
        foreach ($devices as $key => $device) {
          if (strpos($device_details, $device) > 0) {
            if($device ==  'ios') {
              $device_name = 'iphone';
            } else {
              $device_name = $device;
            }
            break;
          }
        }
      }
      return $device_name;
    }

    public function save_api_activity_log (Request $request) {

      try {

        $error = 0;
        $error_message = '';
        /*
        if( isset($request['task']) && $request['task'] != 'login' ) {
          $appactivitylog = Appactivitylog::select('action', 'ip_address', 'device_os', 'device_name', 'device_version', 'created_at')
                              ->where('user_id', $request->user()->id)
                              ->orderBy('id', 'desc')->first();
          if($appactivitylog) {
            //if( $appactivitylog->ip_address != $request->header('ip-address') ||
            if( $appactivitylog->device_os != $request->header('device-os') ||
                $appactivitylog->device_version != $request->header('device-version') ||
                $appactivitylog['device_name'] != $this->get_device_name($request)
              ) {
                $error = 1;
                //$error_msg = $appactivitylog->ip_address .' - '.$request->header('ip-address');
                //$error_msg = 'Our system detected that you were active on '.$appactivitylog->device_name.' device last '.$appactivitylog->created_at. '. Please logout first so you can get access on this device.';
                $error_msg = 'Only one device per subscription is allowed.  Another device ('.$appactivitylog->device_name.') is currently logged in with your user credentials.';
              }
          }
        }
        */

        if($error > 0) {
          $error_message = $error_msg;
        } else {
          $error_message = '';

          $ipAddress = $_SERVER['REMOTE_ADDR'];

          $appactivitylog = new Appactivitylog();
          $appactivitylog->apiroute = Route::currentRouteAction();
          $appactivitylog->apiroutename = Route::currentRouteName();
          $appactivitylog->user_id = $request->user()->id;
          //$appactivitylog->ip_address = $ipAddress;

          $appactivitylog->ip_address = $request->header('ip-address');
          $appactivitylog->device_os = $request->header('device-os');
          $appactivitylog->device_version = $request->header('device-version');
          $appactivitylog->device_fullinfo = $request->header('device-details');
          $appactivitylog->device_name = $this->get_device_name($request);
          if(isset($request['task'])) {
            $appactivitylog->action = $request['task'];
          }
          $appactivitylog->save();

        }

        return $error_message;

      } catch (\Throwable $th) {

          throw $th;

      }

    }


}
