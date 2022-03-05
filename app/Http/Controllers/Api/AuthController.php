<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\SendFeedbackNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        //return response()->json(['test'=>'test'], 200);
        //return response()->json(['request'=>$request->input('password')], 200);
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){
            //DB::statement('call RemoveOldTokens()');
            $user = Auth::user();
            //return $user;
            //$success['token'] =  $user->createToken('MyApp')-> accessToken;
            $token =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['token' => $token], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }

    /**
     * Generate otp code.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateOtpForLogin(Request $request) {
      $user = User::where('email', $request->email)->first();
      if(!$user) {
        return response()->json(['message' => 'We can\'t find a user with that email address. ', 'status' => 'error'], 422);
      }
      else {
        $otp_code = rand(111111,999999);
        $user->otp_code = $otp_code;
        $user->otp_generated_at = date('Y-m-d H:i:s');
        $user->save();

        $message = 'Your AllThingsMichaelMcLean profile was used to login on '.date("j F Y, g:i a").'. To proceed, '.$otp_code.' is your One-Time Password(OTP). This OTP is valid for 10 minutes.';

        // send email, if possible will send it through sms
        $mail_data = array();
        $mail_data['email'] = 'admin@songwritersundayschool.com';
        $mail_data['subject'] = 'You OTP Code from AllThingsMichaelMcLean';
        $mail_data['message'] = '
                        Hi ,
                        <br><br>
                        '.$message.'
                        <br><br>
                        Cheers,<br>
                    ';

        Notification::route('mail', $request->email)->notify(new SendFeedbackNotification($mail_data));
        return response()->json(['user' => $user, 'status' => 'success'], 200);
      }
    }

    /**
     * Verify otp code for login.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyOtpForLogin(Request $request) {
      $user = User::where('otp_code', $request->otp_code)->first();
      if(!$user)
        return response()->json(['message' => 'Invalid OTP code. ', 'status' => 'error'], 422);
      else {
        $datetime2 = strtotime(date('Y-m-d H:i:s'));
        $datetime1 = strtotime($user->otp_generated_at);
        $secs = $datetime2 - $datetime1;
        $mins = $secs/60;
        if($mins > 15) {  // valid for 15 minutes only
          $user->otp_code = null;
          $user->otp_generated_at = null;
          $user->save();

          return response()->json(['message' => 'Expired OTP code. ', 'status' => 'error'], 422);
        } else {
          $user->otp_code = null;
          $user->otp_generated_at = null;
          $user->save();

          $user = Auth::loginUsingId($user->id);
          $token =  $user->createToken('MyApp')->accessToken;
          return response()->json(['token' => $token, 'status' => 'success'], 200);
        }
      }
    }


}
