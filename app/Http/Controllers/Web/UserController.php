<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\CreditCard;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\InfusionsoftToken;
use App\Models\InfusionsoftLog;
use App\Models\InfusionsoftTrialLog;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFeedbackNotification;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if($this->isCurrentUserAdmin())
                abort(403);

            $user = Auth::user();

            $inTrialfLog = InfusionsoftTrialLog::select('still_trial','contact_id')
                                            ->where('user_id', '=', $user->id)
                                            ->orderBy('id', 'desc')->first();

            if( isset($inTrialfLog->still_trial) && $inTrialfLog->still_trial == 1 ) {
                $subscription_type = 'TRIAL';
                $inf_subscrip_status = 'TRIAL';

                $api_method = 'GET';
                $end_point = 'contacts/'.$inTrialfLog->contact_id.'/creditCards';
                $infusionsoft_data = $this->api_send_request_to_infusion_soft($api_method, $end_point);
                $cc_info = $infusionsoft_data;

            } else {

                $infLog = InfusionsoftLog::select('order_id','contact_id')
                                                ->where('user_id', '=', $user->id)
                                                ->orderBy('id', 'desc')->first();

                $api_method = 'GET';
                $end_point = 'orders/'.$infLog->order_id;
                $infusionsoft_data = $this->api_send_request_to_infusion_soft($api_method, $end_point);
                $subscription_type = $infusionsoft_data->order_items[0]->name;

                $api_method = 'GET';
                $end_point = 'contacts/'.$infLog->contact_id.'/creditCards';
                $infusionsoft_data = $this->api_send_request_to_infusion_soft($api_method, $end_point);
                $cc_info = $infusionsoft_data;

                $inf_subscrip_status = '';
                $api_method = 'POST';
                $end_point = '';
                $recur_data = new \stdClass();
                $recur_data->fieldValue = $infLog->order_id;
                $recur_data->fieldName = 'OriginatingOrderId';
                $recur_data->returnField1 = 'Id';
                $xml_data = $this->get_subscription_recurring_data($recur_data);
                $subs_recc_data = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);
                if( isset($subs_recc_data->params->param->value->array->data->value->struct->member->value->i4) ) {
                    $subs_recc_id = $subs_recc_data->params->param->value->array->data->value->struct->member->value->i4;

                    $recur_data = new \stdClass();
                    $recur_data->fieldValue = $subs_recc_id;
                    $recur_data->fieldName = 'Id';
                    $recur_data->returnField1 = 'Status';
                    $xml_data = $this->get_subscription_recurring_data($recur_data);
                    $subs_recc_data = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);
                    $inf_subscrip_status = trim($subs_recc_data->params->param->value->array->data->value->struct->member->value);

                    if($inf_subscrip_status == 'Inactive') {
                        $user->subscription_status = 'CANCELLED';
                        $user->save();
                    } elseif($inf_subscrip_status == 'Active') {
                        $user->subscription_status = 'PAID';
                        $user->save();
                    }
                }

            }

            return view('account_page.index', [
                'credit_cards' => $cc_info,
                'subscription_type' => $subscription_type,
                'inf_subscrip_status' => $inf_subscrip_status,
            ]);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateCredential(Request $request)
    {
        try {
            $this->validate($request, [
                'email'  =>  'required|email',
                'password' => "required|min:6",
                'confirm_password' => "required:min:6|same:new_password",
                'new_password' => "required:min:6",
                //'credit_card_id'  =>  'required|exists:credit_cards,id'
            ]);

            $user = Auth::user();
            $item = User::findOrFail($user->id);

            if (Hash::check($request->password, $item->password)) {
                $item->email = $request->email;
                $item->password = bcrypt($request->new_password);
                $item->credit_card_id = $request->credit_card_id;
                $item->subscription_type_id = $request->subscription_type_id;
                $item->save();

                if ($user->isAdmin) {

                    // return Redirect::to('admin_dashboard');

                } else {
                    return response()->json( ['success' => 'Password Successfully Changed!.' ] );
                }
            } else {
                return response()->json( ['errors' => ['password'=>['Invalid current password.'] ] ], 422 );
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function get_subscription_recurring_data ($obj_data)
    {
        $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
            <methodCall>
              <methodName>DataService.findByField</methodName>
              <params>
                <param><value><string>infusionsoft_token_value_here</string></value></param>
                <param><value><string>RecurringOrder</string></value></param>
                <param><value><int>1</int></value></param>
                <param><value><int>0</int></value></param>
                <param><value><string>'.$obj_data->fieldName.'</string></value></param>
                <param><value><string>'.$obj_data->fieldValue.'</string></value></param>
                <param><value><array><data><value><string>'.$obj_data->returnField1.'</string></value></data></array></value></param>
              </params>
            </methodCall>';
        return $xml_data;
    }

    private function get_cancel_subscription_recurring_data ($obj_data)
    {
        $_data = InfusionsoftToken::orderBy('id', 'desc')->first();
        $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
            <methodCall>
                <methodName>DataService.update</methodName>
                <params>
                    <param><value><string>infusionsoft_token_value_here</string></value></param>
                    <param><value><string>RecurringOrder</string></value></param>
                    <param><value><int>'.$obj_data->subs_recc_id.'</int></value></param>
                    <param><value><struct><member><name>'.$obj_data->field_name.'</name><value><string>'.$obj_data->field_value.'</string></value></member></struct></value></param>
                </params>
            </methodCall>';
        return $xml_data;
    }

    public function cancelSubscription(Request $request)
    {
        try {
            $user = Auth::user();
            $infLog = InfusionsoftLog::select('order_id','contact_id')
                                            ->where('user_id', '=', $user->id)
                                            ->orderBy('id', 'desc')->first();

            $error_msg = '';
            $error = true;
            $status_code = 403;

            $api_method = 'POST';
            $end_point = '';

            $recur_data = new \stdClass();
            $recur_data->fieldValue = $infLog->order_id;
            $recur_data->fieldName = 'OriginatingOrderId';
            $recur_data->returnField1 = 'Id';
            $xml_data = $this->get_subscription_recurring_data($recur_data);
            $subs_recc_data = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);
            if( isset($subs_recc_data->params->param->value->array->data->value->struct->member->value->i4) ) {
                $subs_recc_id = $subs_recc_data->params->param->value->array->data->value->struct->member->value->i4;

                // update Status field
                $cancel_subscription_data = new \stdClass();
                $cancel_subscription_data->subs_recc_id = $subs_recc_id;
                $cancel_subscription_data->field_name = 'Status';
                $cancel_subscription_data->field_value = 'Inactive';
                $xml_data = $this->get_cancel_subscription_recurring_data($cancel_subscription_data);
                $status_result = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);

                // update ReasonStopped field
                $cancel_subscription_data = new \stdClass();
                $cancel_subscription_data->subs_recc_id = $subs_recc_id;
                $cancel_subscription_data->field_name = 'ReasonStopped';
                $cancel_subscription_data->field_value = trim($request->reason_to_stopped);
                $xml_data = $this->get_cancel_subscription_recurring_data($cancel_subscription_data);
                $reason_stopped_result = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);

                // update EndDate field
                $cancel_subscription_data = new \stdClass();
                $cancel_subscription_data->subs_recc_id = $subs_recc_id;
                $cancel_subscription_data->field_name = 'EndDate';
                $cancel_subscription_data->field_value = date('m-d-Y');
                $xml_data = $this->get_cancel_subscription_recurring_data($cancel_subscription_data);
                $endDate_result = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);

                if( isset($status_result->params->param->value->i4) ) {
                    $user->subscription_status = 'CANCELLED';
                    $user->save();

                    $error = false;
                    $status_code = 200;
                    $error_msg = 'Subscription Successfully Cancelled!';


                    // notify admin
                    $mail_data = array();
                    $mail_data['email'] = 'admin@songwriter.com';
                    $mail_data['subject'] = 'User Cancelled a Subscription';
                    $mail_data['message'] = '
                                    Hey Dude,
                                    <br><br>
                                    Just want to notify you that there is a user who just cancelled there subscription on SongWriterSundaySchool.com

                                    <br><br>
                                    <strong>Details Info</strong>
                                    Name: '.$user->name.' <br>
                                    Email: '.$user->email.' <br>
                                    Subscription Type: '.$request->subscription_type.' <br>
                                    Subscription Status: '.$request->inf_subscrip_status.' <br>
                                    Date Cancelled : '.date('m-d-Y').' <br>

                                    <br><br>
                                    Cheers,<br>
                                ';
                    Notification::route('mail', 'admin@songwriter.com')
                                ->notify(new SendFeedbackNotification($mail_data));

                    // notify end-user
                    $mail_data = array();
                    $mail_data['email'] = 'admin@songwriter.com';
                    $mail_data['subject'] = 'User Cancelled a Subscription';
                    $mail_data['message'] = '
                                    Hi '.$user->name.',
                                    <br><br>
                                    Your account has been cancelled in SongWriterSundaySchool.com
                                    <br><br>
                                    Please contact us if youre not the one who made this action. <br>
                                    <br><br>
                                    Thanks,<br>
                                ';
                    Notification::route('mail', $user->email)
                                ->notify(new SendFeedbackNotification($mail_data));
                } else {
                    if( isset($status_result->fault->value->struct->member[1]->value) ) {
                        $error_msg = $status_result->fault->value->struct->member[1]->value;
                    } else {
                        $error_msg = 'Error occured while sending request to Infusionsoft.';
                    }
                }
            } else {
                $error_msg = 'Subscription Recurring Plan ID doesnt exist.';
            }

            return response()->json( ['error' => $error, 'message' => $error_msg ], $status_code );

        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
