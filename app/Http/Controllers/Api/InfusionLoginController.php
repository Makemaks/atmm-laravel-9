<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp;
use App\Http\Requests\InfusionForm\Store;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_encode;

class InfusionLoginController extends Controller
{
    
    public function signUp (Store $request) {

        $client = new GuzzleHttp\Client([
            'allow_redirects' => false
        ]);

        $url = "https://nv681.infusionsoft.com/app/form/process/bc186b4af60c114f6297f3c069d44426";

        $body = [
            '_token' => $request->_token,
            'inf_form_xid' => $request->inf_form_xid,
            'inf_form_name' => $request->inf_form_name,
            'inf_field_FirstName' => $request->inf_field_FirstName,
            'inf_field_LastName' => $request->inf_field_LastName,
            'inf_field_Email' => $request->inf_field_Email,
            'g-recaptcha-response' => $request['g-recaptcha-response'],
            'timeZone' => $request->timeZone
        ];
        $client->post($url,  $body);

        return view('thank_you.index');

    }

    public function validateSignUp (Store $request) {
        $status = 'error';
        if(trim($request['g-recaptcha-response']))    {   
            $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcTo7cUAAAAAARtqZ4LJenXHAVRmhAY-dzxiUU-&response=".$request['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            if($response['success'] == false)   {   
                $message = "Google captcha response is invalid.";
            }
            else {
                $status = 'success';
                $message = "valid";
            }
        }
        else {
            $message = "Google captcha response is invalid.";
        }
        $result = array();
        $result['status'] = $status;
        $result['message'] = $message;
        echo json_encode($result);
    }

    
}
