<?php

namespace App\Http\Controllers\Auth;

use Google2FA;
use Auth;
use Session;

use App\Models\Nmitransactions;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function doLogin (Request $request) {


      /*
      $isGoogleValid = 1;
      if($request->email == 'admin@songwriter.com') {
        if(!$request->one_time_password) {
          return back()->with('error', 'One time password is required');
        }

        $secret = $request->one_time_password;
        $window = 8;
        $isGoogleValid = Google2FA::verifyKey('JA5F4SKHZIFA25GW', $secret, $window);
        if(!$isGoogleValid) {
            $isGoogleValid = 0;
            return back()->with('error', 'Invalid OTP');
        }
      }
      */

        $userwhotriedtologgedin = User::where('email', $request->email)->first();
        if($userwhotriedtologgedin) {
            if($userwhotriedtologgedin->subscription_status != 'FORCE_STOP') {
              if( $userwhotriedtologgedin->isAdmin == 0 && trim($request->email) != 'devapi@songwriter.com' && trim($request->email) != 'user@songwriter.com' && trim($request->email) != 'google@songwriter.com' && trim($request->email) != 'apple@songwriter.com' ) {
                $nmitransactions = Nmitransactions::select('transaction_id', 'email',
                                                         'source', 'success', 'condition',
                                                         'date', 'date_formatted')
                                  ->where('email', $request->email)
                                  ->orderBy('id', 'desc')->first();
                if($nmitransactions) {
                    if($nmitransactions->success == 0 || $nmitransactions->condition == 'failed') {
                      $userwhotriedtologgedin->subscription_status = 'INACTIVE';
                    } else {
                        if ( strtotime($nmitransactions->date_formatted) < time() - strtotime('-2 months')) {
                          $userwhotriedtologgedin->subscription_status = 'INACTIVE';
                        } else {
                          $userwhotriedtologgedin->subscription_status = 'PAID';
                        }
                    }
                } else {
                  $userwhotriedtologgedin->subscription_status = 'INACTIVE';
                }
                $userwhotriedtologgedin->save();
              }
            }
        }


        $login = array(
            'email'     => $request->email,
            'password'  => $request->password,
            'subscription_status'  => 'PAID'
        );

        if( trim($request->email) == 'devapi@songwriter.com' )
            return back()->with('error', 'Invalid Access!');

        //if( trim($request->email) != 'user@songwriter.com' || rim($request->email) != 'admin@songwriter.com' )
          //return back()->with('error', 'Invalid Access!');

        if (Auth::attempt($login)) {

            $user = Auth::user();
            Session::put('user', $user);

            if ($user->isAdmin) {

                return Redirect::to('dashboard');

            } else {
                //return Redirect::to('videos');
                return Redirect::to('songs');
            }

        } else {

            //return back()->with('error', 'Email or Password is Incorrect!');
            return back()->with('error', 'Email or Password is Incorrect or Not a Paid Subscriber!');

        }
    }
}
