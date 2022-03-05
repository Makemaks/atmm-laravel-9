<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFeedbackNotification;
use App\Http\Requests\InfusionSoft\Store;
use App\Http\Controllers\Controller;
use App\Models\InfusionsoftToken;
use App\Models\InfusionsoftLog;
use App\Models\InfusionsoftTrialLog;
use App\Models\User;
use App\Models\Settings;
use CountryState;
use Auth;
use Session;

class InfusionsoftController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe (Store $request) {
        try {

            $user = User::where('email', '=', $request->input('email') )->first();
            if($user)
                return response()->json( ['error' => true, 'message' => 'Email already exist.' ], 403 );

            $periodic = trim( $request->input('periodic') );
            if ($periodic != 'monthly' && $periodic != 'yearly')   { // need to validate in case data are tampered
                return response()->json( ['error' => true, 'message' => 'Please select a subscription plan.' ], 403 );
            }

            $response = $this->getInfusionsoftProducts();
            $products = $response['data'];
            $price = 0;
            $tax = 0;
            if( count($products) > 0 ) {
              foreach($products as $ind => $product) {
                $product_name = trim(strtolower(str_replace('Subscription', '', $product->product_name)));
                if (isset($product->subscription_plans) && count($product->subscription_plans) > 0) {
                  foreach ($product->subscription_plans as $subscription_plan) {
                    if ( $periodic == 'monthly' && $product_name == 'monthly' && $subscription_plan->active == 1 && $subscription_plan->is_hide == 0) {
                      $price = $subscription_plan->plan_price;

                      $selected_product_id = $product->id;
                      $selected_subscription_id = $subscription_plan->id;
                      $numberPmts = 12;
                      $daysBetweenPmts = 30;
                      $sales_tax = 0;
                      $product_price = $price;
                      $initialPmt_price = $product_price + $sales_tax;
                    }
                    if ( $periodic == 'yearly' && $product_name == 'yearly' && $subscription_plan->active == 1 && $subscription_plan->is_hide == 0) {
                      $price = $subscription_plan->plan_price;

                      $selected_product_id = $product->id;
                      $selected_subscription_id = $subscription_plan->id;
                      $numberPmts = 5;
                      $daysBetweenPmts = 365;
                      $sales_tax = 0;
                      $product_price = $price;
                      $initialPmt_price = $product_price + $sales_tax;
                    }
                  }
                }
              }
            }

            $email_add = array();
            $email_add['email'] = trim( $request->input('email') );
            $email_add['field'] = 'EMAIL1';
            $email_addresses = array();
            $email_addresses[] = $email_add;

            $address = array();
            $address['country_code'] = trim( $request->input('country') );
            $address['field'] = 'BILLING';
            $address['line1'] = trim( $request->input('addrline1') );
            $address['line2'] = trim( $request->input('addrline2') );
            $address['locality'] = trim( $request->input('city') );
            $address['postal_code'] = trim( $request->input('zipcode') );
            $address['region'] = trim( $request->input('state') );
            $address['zip_code'] = trim( $request->input('zipcode') );
            $addresses = array();
            $addresses[] = $address;

            $phone_num = array();
            $phone_num['number'] = trim( $request->input('phone') );
            $phone_num['field'] = 'PHONE1';
            $phone_num['type'] = 'Mobile';
            $phone_numbers = array();
            $phone_numbers[] = $phone_num;

            $origin = array();
            $origin['ip_address'] = request()->ip();

            $post_data = array();
            $post_data['email_addresses'] = $email_addresses;
            $post_data['addresses'] = $addresses;
            $post_data['phone_numbers'] = $phone_numbers;
            $post_data['given_name'] = trim( $request->input('fname') );
            $post_data['family_name'] = trim( $request->input('lname') );
            $post_data['source_type'] = 'WEBFORM';
            $post_data['opt_in_reason'] = 'Songwritersundayschool Subscriber';
            $post_data['origin'] = $origin;

            $error_msg = '';
            $error = true;
            $status_code = 403;

            $post_data = json_encode($post_data);
            $api_method = 'POST';
            $end_point = 'contacts';
            $inf_contact = $this->api_send_request_to_infusion_soft($api_method, $end_point, '',$post_data);

            if( isset($inf_contact->id) ) {

                // add credit card to contact
                $cc_post_data = array();
                $cc_post_data['address'] = $address;
                $cc_post_data['card_number'] = trim( $request->input('cc_number') );
                $cc_post_data['card_type'] = trim( $request->input('cardType') );
                $cc_post_data['email_address'] = trim( $request->input('email') );
                $cc_post_data['expiration_month'] = trim( $request->input('expirationMonth') );
                $cc_post_data['expiration_year'] = trim( $request->input('expirationYear') );
                $cc_post_data['name_on_card'] = trim( $request->input('name_on_card') );
                $post_data = json_encode($cc_post_data);
                $api_method = 'POST';
                $end_point = 'contacts/'.$inf_contact->id.'/creditCards';
                $add_credit_card = $this->api_send_request_to_infusion_soft($api_method, $end_point, '',$post_data);

                if( isset($add_credit_card->validation_status) && $add_credit_card->validation_status == 'Good' ) {

                    $processSpecials = 0;
                    $promoCodes = '';
                    if ($periodic == 'monthly') {

                    }
                    if ($periodic == 'yearly') {
                        if( trim($request->input('apply_discount')) == 'FCFANS19' ) {
                            $processSpecials = 1;
                            $promoCodes = 'FCFANS19';

                            $sales_tax = 0;
                            $product_price = 106.00;
                            $initialPmt_price = $product_price + $sales_tax;
                        }
                    }

                    // place an order
                    $order_data = new \stdClass();
                    $order_data->contactId = $inf_contact->id;
                    $order_data->creditCardId = $add_credit_card->id;
                    $order_data->productIds = $selected_product_id;
                    $order_data->subscriptionPlanIds = $selected_subscription_id;
                    $order_data->processSpecials = $processSpecials;
                    $order_data->promoCodes = $promoCodes;
                    $post_data = $this->place_an_order_data($order_data);

                    $api_method = 'POST';
                    $end_point = '';
                    $add_an_order = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$post_data);
                    if( isset($add_an_order->fault) ) { //if there is error when creating the order
                        $error_msg = $add_an_order->fault->value->struct->member[1]->value;
                    }
                    else {

                        if( $periodic == 'yearly' && trim($request->input('apply_discount')) == 'FCFANS19' ) {
                             // Retrieve Order Info to remove the additional order item
                            $result_order_id = $add_an_order->params->param->value->struct->member[1]->value;
                            $addtional_order_items = array();

                            $api_method = 'GET';
                            $end_point = 'orders/'.$result_order_id;
                            $ord_result = $this->api_send_request_to_infusion_soft($api_method, $end_point);

                            if( isset($ord_result->order_items) && count($ord_result->order_items) > 0 ) {
                                $count_special_product = 0;
                                $special_products = array();
                                foreach($ord_result->order_items as $order_item) {
                                    if($order_item->type == 'Product') {
                                        $addtional_order_items[] = $order_item->id;
                                    }
                                    if($order_item->type == 'Special Product') {
                                        $count_special_product++;
                                        $special_products[] = $order_item->id;
                                    }
                                }
                                if($count_special_product > 1) {
                                    $addtional_order_items[] = $special_products[0]; // special product first item
                                }
                            }
                            if( count($addtional_order_items) > 0 ) {
                                //remove the additional order item
                                foreach($addtional_order_items as $addtional_order_item) {
                                    $api_method = 'DELETE';
                                    $end_point = 'orders/'.$result_order_id.'/items/'.$addtional_order_item;
                                    $this->api_send_request_to_infusion_soft($api_method, $end_point);

                                }
                            }
                        }

                        $initial_date = date('Y-m-d H:i:s');
                        $date = new \DateTime($initial_date);
                        $initial_date = date('Ymd\TH:i:s', strtotime($initial_date));
                        //$initial_date = date('Y-m-d\TH:i:s', strtotime($initial_date));


                        $initial_date = $date->format('Y-m-d H:i:s');
                        $initial_date = date('Ymd\TH:i:s', strtotime($initial_date));
                        //$plan_start_date = $date->format('Y-m-d H:i:s');
                        //$plan_start_date = date('Ymd\TH:i:s', strtotime($plan_start_date));

                        if ($periodic == 'monthly') {
                            $date->add(new \DateInterval('P1M')); // 1 month period
                        } else {
                            $date->add(new \DateInterval('P1Y')); // 1 month period
                        }

                        //$date->add(new \DateInterval('P1M')); // 1 month period
                        $plan_start_date = $date->format('Y-m-d H:i:s');
                        $plan_start_date = date('Ymd\TH:i:s', strtotime($plan_start_date));

                        // create recurring payment
                        $recur_data = new \stdClass();
                        $recur_data->invoiceId = $add_an_order->params->param->value->struct->member[2]->value;
                        $recur_data->autoCharge = 1;
                        $recur_data->creditCardId = $add_credit_card->id;
                        //$recur_data->merchantAccountId = 2; // hardcoded Merchant ID
                        $recur_data->merchantAccountId = 6; // hardcoded Merchant ID
                        $recur_data->daysRetry = 3;
                        $recur_data->maxRetry = 3;
                        $recur_data->initialPmt = $initialPmt_price;
                        $recur_data->initialPmtDate = $initial_date;
                        $recur_data->planStartDate = $plan_start_date;
                        $recur_data->numPmts = $numberPmts; //The number of payments in the plan, not including the first payment
                        $recur_data->daysBetweenPmts = $daysBetweenPmts;
                        $post_data = $this->payment_recurring_data($recur_data);

                        $api_method = 'POST';
                        $end_point = '';
                        $create_recurring_payment = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$post_data);

                        if( isset($create_recurring_payment->fault) ) { //if there is error when creating the order
                            $error_msg = $create_recurring_payment->fault->value->struct->member[1]->value;
                        } else { // success

                            $result_order_id = $add_an_order->params->param->value->struct->member[1]->value;

                             // Retrieve Order Transactions
                            $order_status = '';
                            $api_method = 'GET';
                            $end_point = 'orders/'.$result_order_id.'/transactions';
                            $ord_tran = $this->api_send_request_to_infusion_soft($api_method, $end_point);

                            if($ord_tran)   {
                                if( count($ord_tran->transactions) > 0 ) {
                                    if( count($ord_tran->transactions[0]->orders) > 0 )
                                        $order_status = $ord_tran->transactions[0]->orders[0]->status;
                                }
                            }

                            //$newuser = User::findOrFail($user->id);
                            //$newuser->subscription_status = $order_status;
                            //$newuser->save();

                            if( $order_status == 'PAID' ) {
                                $user = new User();
                                $user->name = trim( $request->input('fname') ).' '.trim( $request->input('lname') );
                                $user->email = trim( $request->input('email') );
                                $user->password = bcrypt( trim( $request->input('password') ) );
                                $user->credit_card_id = $add_credit_card->id;
                                $user->subscription_type_id = $selected_product_id;
                                $user->subscription_status = $order_status;
                                $user->save();

                                $inf_log = new InfusionsoftLog();
                                $inf_log->user_id = $user->id;
                                $inf_log->contact_id = $inf_contact->id;
                                $inf_log->creditcard_id = $add_credit_card->id;
                                $inf_log->order_id = $result_order_id ;
                                $inf_log->invoice_id = $add_an_order->params->param->value->struct->member[2]->value;
                                $inf_log->order_status = $order_status;
                                $inf_log->save();

                                 // apply the "Subscription Purchased" tag to the contact
                                $tags = array('154');
                                $alltags['tagIds'] = $tags;
                                $post_data = json_encode($alltags);

                                $api_method = 'POST';
                                $end_point = 'contacts/'.$inf_contact->id.'/tags';
                                $apply_tags = $this->api_send_request_to_infusion_soft($api_method, $end_point, '',$post_data);

                                $error = false;
                                $status_code = 200;
                                $error_msg = 'Thank you for subscribing to Songwriter Sunday School!';

                                $this->notifyAdminSubsriber($request);

                                Auth::loginUsingId($user->id);
                                $user = Auth::user();
                                Session::put('user', $user);
                                Session::put('new_subscriber_user', 1); // for FB pixel Subscribe
                            } else { //if the payment is not successful
                                $error_msg = 'Problem occured when deducting the amount in the card.';

                                // remove the contact in infusionsoft if the payment is not successful
                                $api_method = 'DELETE';
                                $end_point = 'contacts/'.$inf_contact->id;
                                $apply_tags = $this->api_send_request_to_infusion_soft($api_method, $end_point);
                            }
                        }
                    }
                } else { //if there is error when adding the credit card
                    $error_msg = $add_credit_card->message;

                    // remove the contact in infusionsoft if there is no valid credit card to avoid duplicate entries
                    $api_method = 'DELETE';
                    $end_point = 'contacts/'.$inf_contact->id;
                    $apply_tags = $this->api_send_request_to_infusion_soft($api_method, $end_point);
                }
            } else { //if there is error when creating the contact
                $error_msg = $inf_contact->message;
            }

            return response()->json( ['error' => $error, 'message' => $error_msg ], $status_code );

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    private function notifyAdminSubsriber ($request)
    {
      // notify admin
      $mail_data = array();
      $mail_data['email'] = $request->input('email');
      $mail_data['subject'] = 'New User Payment';
      $mail_data['message'] = '
                      Hey Dude,
                      <br><br>
                      Just want to notify you that there is a new user who just successfully subscribed on SongWriterSundaySchool.com
                      <br><br>
                      Cheers,<br>
                  ';
      Notification::route('mail', 'admin@songwritersundayschool.com')->notify(new SendFeedbackNotification($mail_data));

      // notify subscriber
      $mail_data = array();
      $mail_data['email'] = 'admin@songwritersundayschool.com';
      $mail_data['subject'] = 'Welcome to SongWriter Sunday School';
      $mail_data['message'] = '
                      Hi '.$request->input('fname').' '.$request->input('lname').',
                      <br><br>
                      Congrats! You\'ve been successfully registered in SongWriterSundaySchool.com
                      <br><br>
                      Cheers,<br>
                  ';
      Notification::route('mail', $request->input('email'))->notify(new SendFeedbackNotification($mail_data));

    }

    private function place_an_order_data ($obj_data)
    {
        $_data = InfusionsoftToken::orderBy('id', 'desc')->first();
        $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
            <methodCall>
              <methodName>OrderService.placeOrder</methodName>
              <params>
                <param><value><string>'.$_data->access_token.'</string></value></param>
                <param><value><int>'.$obj_data->contactId.'</int></value></param>
                <param><value><int>'.$obj_data->creditCardId.'</int></value></param>
                <param><value><int>0</int></value></param>
                <param><value><array><data><value><int>'.$obj_data->productIds.'</int></value></data></array></value></param>
                <param><value><array><data><value><int>'.$obj_data->subscriptionPlanIds.'</int></value></data></array></value></param>
                <param><value><boolean>'.$obj_data->processSpecials.'</boolean></value></param>
                <param><value><array><data><value><string>'.$obj_data->promoCodes.'</string></value></data></array></value></param>
                <param><value><int>0</int></value></param>
                <param><value><int>0</int></value></param>
              </params>
            </methodCall>';
        return $xml_data;
    }

    private function payment_recurring_data ($obj_data)
    {
        $_data = InfusionsoftToken::orderBy('id', 'desc')->first();
        $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
            <methodCall>
              <methodName>InvoiceService.addPaymentPlan</methodName>
              <params>
                <param><value><string>'.$_data->access_token.'</string></value></param>
                <param><value><int>'.$obj_data->invoiceId.'</int></value></param>
                <param><value><boolean>'.$obj_data->autoCharge.'</boolean></value></param>
                <param><value><int>'.$obj_data->creditCardId.'</int></value></param>
                <param><value><int>'.$obj_data->merchantAccountId.'</int></value></param>
                <param><value><int>'.$obj_data->daysRetry.'</int></value></param>
                <param><value><int>'.$obj_data->maxRetry.'</int></value></param>
                <param><value><double>'.$obj_data->initialPmt.'</double></value></param>
                <param><value><dateTime.iso8601>'.$obj_data->initialPmtDate.'</dateTime.iso8601></value></param>
                <param><value><dateTime.iso8601>'.$obj_data->planStartDate.'</dateTime.iso8601></value></param>
                <param><value><int>'.$obj_data->numPmts.'</int></value></param>
                <param><value><int>'.$obj_data->daysBetweenPmts.'</int></value></param>
              </params>
            </methodCall>';
        return $xml_data;
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getToken () {
        $this->get_access_token ();
        $_db_data = InfusionsoftToken::orderBy('id', 'desc')->first();
        return response()->json( ['infusionsoft_token' => $_db_data->access_token] );
    }


    public function refresh_access_token (Request $request) {
        try {
            $this->get_access_token ();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function get_access_token () {
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
                /*
                $id = DB::table('infusionsoft_tokens')->insertGetId([
                        'access_token' => $_data->access_token,
                        'refresh_token' => $_data->refresh_token,
                        'token_type' => $_data->token_type,
                        'expires_in' => $_data->expires_in,
                        'scope' => $_data->scope
                    ]);
                */
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    private function getPriceProductById($productID, $planID) {
      $price = 0;
      $api_method = 'GET';
      $end_point = 'products/'.$productID;
      $result = $this->api_send_request_to_infusion_soft($api_method, $end_point);
      if( count($result->subscription_plans) > 0 ) {
        foreach($result->subscription_plans as $ind => $subscription_plan) {
          if( $subscription_plan->active == 1 && $subscription_plan->id == $planID ) {
            $price = $subscription_plan->plan_price;
          }
        }
      }
      return $price;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getCompletedTrials(Request $request)
    {
        //$daysTillTrialEnds = 14;

        $id = 'trial_days';
        $settings = Settings::findOrFail($id);
        $daysTillTrialEnds = $settings->value;
        if ($daysTillTrialEnds < 1 ) {
          $daysTillTrialEnds = 14;
        }

        $trial_logs = InfusionsoftTrialLog::select('id','user_id','contact_id','creditcard_id','product_id','product_label','still_trial','subscription_plan_id','created_at','updated_at')
                            ->where('still_trial', '=', '1')
                            ->whereRaw('created_at < NOW() - INTERVAL ? DAY', $daysTillTrialEnds)
                            ->orderBy('id', 'asc')->get();

        $all_result = array();
        $error_msg = '';
        $error = true;
        $status_code = 403;
        $processSpecials = 0;
        $promoCodes = '';
        if( count($trial_logs) > 0 ) {
            foreach($trial_logs as $ind => $trial_log) { // trial log loop

              if( ($trial_log->contact_id > 0) && ($trial_log->creditcard_id > 0) && ($trial_log->product_id > 0) && ($trial_log->subscription_plan_id > 0) ) {

                  $selected_product_id = $trial_log->product_id;
                  $selected_subscription_id = $trial_log->subscription_plan_id;
                  if ($trial_log->product_label == 'monthly') {
                    $numberPmts = 12;
                    $daysBetweenPmts = 30;
                  }
                  if ($trial_log->product_label == 'yearly') {
                    $numberPmts = 5;
                    $daysBetweenPmts = 365;
                  }
                  $initialPmt_price = $this->getPriceProductById($trial_log->product_id, $trial_log->subscription_plan_id);

                  // place an order
                  $order_data = new \stdClass();
                  $order_data->contactId = $trial_log->contact_id;
                  $order_data->creditCardId = $trial_log->creditcard_id;
                  $order_data->productIds = $trial_log->product_id;
                  $order_data->subscriptionPlanIds = $trial_log->subscription_plan_id;
                  $order_data->processSpecials = $processSpecials;
                  $order_data->promoCodes = $promoCodes;

                  $post_data = $this->place_an_order_data($order_data);
                  $api_method = 'POST';
                  $end_point = '';
                  $add_an_order = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$post_data);
                  if( isset($add_an_order->fault) ) { //if there is error when creating the order
                      $error_msg = $add_an_order->fault->value->struct->member[1]->value;
                  }
                  else {
                    $initial_date = date('Y-m-d H:i:s');
                    $date = new \DateTime($initial_date);

                    $initial_date = $date->format('Y-m-d H:i:s');
                    $initial_date = date('Ymd\TH:i:s', strtotime($initial_date));

                    $plan_start_date = $date->format('Y-m-d H:i:s');
                    $plan_start_date = date('Ymd\TH:i:s', strtotime($plan_start_date));



                    // create recurring payment
                    $recur_data = new \stdClass();
                    $recur_data->invoiceId = $add_an_order->params->param->value->struct->member[2]->value;
                    $recur_data->autoCharge = 1;
                    $recur_data->creditCardId = $trial_log->creditcard_id;
                    //$recur_data->merchantAccountId = 2; // hardcoded Merchant ID
                    $recur_data->merchantAccountId = 6; // hardcoded Merchant ID
                    $recur_data->daysRetry = 3;
                    $recur_data->maxRetry = 3;
                    $recur_data->initialPmt = $initialPmt_price;
                    $recur_data->initialPmtDate = $initial_date;
                    $recur_data->planStartDate = $plan_start_date;
                    $recur_data->numPmts = $numberPmts; //The number of payments in the plan, not including the first payment
                    $recur_data->daysBetweenPmts = $daysBetweenPmts;
                    $post_data = $this->payment_recurring_data($recur_data);

                    $api_method = 'POST';
                    $end_point = '';
                    $create_recurring_payment = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$post_data);

                    if( isset($create_recurring_payment->fault) ) { //if there is error when creating the recurring payment
                        $error_msg = $create_recurring_payment->fault->value->struct->member[1]->value;
                    } else { // success

                        $result_order_id = $add_an_order->params->param->value->struct->member[1]->value;
                         // Retrieve Order Transactions
                        $order_status = '';
                        $api_method = 'GET';
                        $end_point = 'orders/'.$result_order_id.'/transactions';
                        $ord_tran = $this->api_send_request_to_infusion_soft($api_method, $end_point);

                        if($ord_tran)   {
                            if( count($ord_tran->transactions) > 0 ) {
                                if( count($ord_tran->transactions[0]->orders) > 0 )
                                    $order_status = $ord_tran->transactions[0]->orders[0]->status;
                            }
                        }

                        if( $order_status == 'PAID' ) { // if order status is paid

                            $current_user = User::findOrFail($trial_log->user_id);
                            $current_user->subscription_status = $order_status;
                            $current_user->save();

                            $inf_log = new InfusionsoftLog();
                            $inf_log->user_id = $trial_log->user_id;
                            $inf_log->contact_id = $trial_log->contact_id;
                            $inf_log->creditcard_id = $trial_log->creditcard_id;
                            $inf_log->order_id = $result_order_id ;
                            $inf_log->invoice_id = $add_an_order->params->param->value->struct->member[2]->value;
                            $inf_log->order_status = $order_status;
                            $inf_log->save();

                             // apply the "Subscription Purchased" tag to the contact
                            $tags = array('154');
                            $alltags['tagIds'] = $tags;
                            $post_data = json_encode($alltags);
                            $api_method = 'POST';
                            $end_point = 'contacts/'.$trial_log->contact_id.'/tags';
                            $apply_tags = $this->api_send_request_to_infusion_soft($api_method, $end_point, '',$post_data);

                            $error = false;
                            $status_code = 200;
                            $error_msg = 'successfully paid';


                            $current_user = InfusionsoftTrialLog::findOrFail($trial_log->id);
                            $current_user->still_trial = 0;
                            $current_user->save();

                            //$this->notifyAdminSubsriber($request);

                        } else { //if the payment is not successful

                            $current_user = User::findOrFail($trial_log->user_id);
                            $current_user->subscription_status = 'TRIAL EXPIRED';
                            $current_user->save();

                            $error_msg = 'Problem occured when deducting the amount in the card.';
                            /*
                            $api_method = 'DELETE';
                            $end_point = 'contacts/'.$inf_contact->id;
                            $apply_tags = $this->api_send_request_to_infusion_soft($api_method, $end_point);
                            */
                        } // end if order status is paid

                    } // end if there is error when creating the recurring payment

                  }  // end if there is error when creating the order

              } else {
                  $error_msg = 'Infusionsoft ContactID, CreditCardID, ProductID or SubscriptionPlanID is empty';
              }

              $all_result[$trial_log->id]['error_msg'] = $error_msg;
              $all_result[$trial_log->id]['error'] = $error;
            } // end trial log loop
        } else {
          $error_msg = 'No trial subscriber found';

        }

        return response()->json( ['error' => $error, 'message' => $error_msg, 'all_result' => $all_result ], $status_code );

    }


}
