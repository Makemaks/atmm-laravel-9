<?php

namespace App\Http\Controllers\Api;

use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Apploginhistory;
use App\Models\Appactivitylog;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doLogin (Request $request) {
        $login = array(
            'email'     => $request->email,
            'password'  => $request->password,
            'subscription_status'  => 'PAID'
        );
        $user_type = '';
        $error = true;
        $status_code = 401;
        if( trim($request->email) == 'devapi@songwriter.com' ) {
            $error_msg = 'Invalid Access!';
        }

        if (Auth::attempt($login)) {
            $user = Auth::user();

            $error_login = 0;
            $appactivitylog = Appactivitylog::select('action', 'ip_address', 'device_os', 'device_name', 'device_version', 'created_at')
                                ->where('user_id', $user->id)
                                ->orderBy('id', 'desc')->first();
            if($appactivitylog) {
              if($appactivitylog->action != 'logout') {
              //if( $appactivitylog['ip_address'] != $request->header('ip-address') || $appactivitylog['device_os'] != $request->header('device-os') || $appactivitylog['device_version'] != $request->header('device-version') || $appactivitylog['device_name'] != $this->get_device_name($request) ) {
                $error_login = 1;
                //$error_msg = 'The system detected that you were active on '.$appactivitylog->device_name.' device last '.$appactivitylog->created_at. '. Please logout first so you can get access on this device.';
                $error_msg = 'Only one device per subscription is allowed.  Another device ('.$appactivitylog->device_name.') is currently logged in with your user credentials.  Do you want to sign out of '.$appactivitylog->device_name.' and continue with this login?';
              }
            }

            if($request->force_login == 1) {
              $error_login = 0;
              $error_msg = '';
              $request['task'] = 'logout';
              $this->save_api_activity_log($request);
            }

            if($error_login > 0) {
              $error = true;
              $error_msg = $error_msg;
              $status_code = 422;
            } else {
              Session::put('user', $user);
              if ($user->isAdmin)
                  $user_type = 'admin';
              else
                  $user_type = 'subscriber';

              $error = false;
              $error_msg = 'success';
              $status_code = 200;

              /********** For Devices Log History ****************/
              $deviceDetails = $request->deviceDetails;
              $ipAddress = $_SERVER['REMOTE_ADDR'];
              /*
              $apploginhistory = new Apploginhistory();
              $apploginhistory->ip_address = $ipAddress;
              $apploginhistory->device_os = $deviceDetails['OS'];
              $apploginhistory->device_version = $deviceDetails['Version'];
              $apploginhistory->device_fullinfo = json_encode($deviceDetails);
              $apploginhistory->user_id = $user->id;
              $apploginhistory->save();

              $request['task'] = 'login';
              $this->save_api_activity_log($request);
              */
            }
        } else {
            $error_msg = 'Email or Password is Incorrect or Not a Paid Subscriber!';
        }

        //return response()->json( [ 'deviceDetails' => $deviceDetails, 'ipAddress' => $ipAddress, 'error' => $error, 'message' => $error_msg, 'user_type' => $user_type, 'user_type' => $user_type ], $status_code );
        return response()->json( [ 'error' => $error, 'message' => $error_msg, 'user_type' => $user_type, 'status_code' => $status_code], $status_code );

    }
}
