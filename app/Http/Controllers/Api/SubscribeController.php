<?php

namespace App\Http\Controllers\Api;

use App\Models\InfusionsoftToken;
use App\Models\InfusionsoftLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFeedbackNotification;
use App\Http\Requests\InfusionSoft\Store as SubscribeValidator;
use App\Http\Controllers\Controller;
use Validator;

class SubscribeController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe (Request $request) {

        $subscribe_validator = new SubscribeValidator();
        $validator = Validator::make($request->all(), $subscribe_validator->rules(), $subscribe_validator->messages() );

        if ($validator->fails())
            return response()->json( ['error' => true, 'result' => $validator->errors() ], 422);

        $user = User::where('email', '=', $request->input('email') )->first();
        if($user)
            return response()->json( ['error' => true, 'message' => 'Email already exist.' ], 403 );

        $periodic = trim( $request->input('periodic') );
        if ($periodic != 'monthly' && $periodic != 'yearly')   { // need to validate in case data are tampered
            return response()->json( ['error' => true, 'message' => 'Please select a subscription plan.' ], 403 );
        }

        $this->get_access_token();
        //$_data = InfusionsoftToken::orderBy('id', 'desc')->first();
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

         // save contacts to infusion soft
        $post_data = json_encode($post_data);
        $post_method = 'contacts';
        $inf_contact = $this->post_to_infusion_soft( $post_data, $post_method );
        if( isset($inf_contact->id) ) {

            /*
             // apply tags to the contact
            $tags = array('154');
            $alltags['tagIds'] = $tags;
            $post_data = json_encode($alltags);
            $post_method = 'contacts/'.$inf_contact->id.'/tags';
            $apply_tags = $this->post_to_infusion_soft($post_data, $post_method);
            */

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
            $post_method = 'contacts/'.$inf_contact->id.'/creditCards';
            $add_credit_card = $this->post_to_infusion_soft($post_data, $post_method);
            if( isset($add_credit_card->validation_status) && $add_credit_card->validation_status == 'Good' ) {
                // harccoded, it would be nice if the data are pulled from the InfusionsotAPI in case there are changes directly in Infusionsoft but I think this is fine for now.

                $processSpecials = 0;
                $promoCodes = '';
                if ($periodic == 'monthly') {
                    $selected_product_id = 10;
                    //$selected_subscription_id = 8;
                    $selected_subscription_id = 12;
                    $numberPmts = 12;
                    $daysBetweenPmts = 30;

                    //$sales_tax = 0.76;
                    $sales_tax = 0;
                    $product_price = 12.72;
                    //$product_price = 6.99;
                    $initialPmt_price = $product_price + $sales_tax;
                }
                if ($periodic == 'yearly') {
                    $selected_product_id = 12;
                    $selected_subscription_id = 10;
                    $numberPmts = 5;
                    $daysBetweenPmts = 365;

                    $sales_tax = 0;
                    //$sales_tax = 7.63;
                    $product_price = 127.20;
                    $initialPmt_price = $product_price + $sales_tax;

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
                $add_an_order = $this->post_to_infusion_soft ($post_data, $post_method, 'xml-rpc');

                if( isset($add_an_order->fault) ) { //if there is error when creating the order
                    $error_msg = $add_an_order->fault->value->struct->member[1]->value;
                }
                else {

                    if( $periodic == 'yearly' && trim($request->input('apply_discount')) == 'FCFANS19' ) {
                         // Retrieve Order Info to remove the additional order item
                        $result_order_id = $add_an_order->params->param->value->struct->member[1]->value;
                        $addtional_order_items = array();
                        $post_method = 'GET';
                        $end_point = 'orders/'.$result_order_id;
                        $ord_result = $this->send_request_infusion_soft($post_method, $end_point);
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
                                $post_method = 'DELETE';
                                $end_point = 'orders/'.$result_order_id.'/items/'.$addtional_order_item;
                                $this->send_request_infusion_soft($post_method, $end_point);
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
                    $create_recurring_payment = $this->post_to_infusion_soft ($post_data, $post_method, 'xml-rpc');

                    if( isset($create_recurring_payment->fault) ) { //if there is error when creating the order
                        $error_msg = $create_recurring_payment->fault->value->struct->member[1]->value;
                    } else { // success

                        $result_order_id = $add_an_order->params->param->value->struct->member[1]->value;

                         // Retrieve Order Transactions
                        $order_status = '';
                        $post_method = 'GET';
                        $end_point = 'orders/'.$result_order_id.'/transactions';
                        $ord_tran = $this->send_request_infusion_soft($post_method, $end_point);
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
                            $post_method = 'contacts/'.$inf_contact->id.'/tags';
                            $apply_tags = $this->post_to_infusion_soft($post_data, $post_method);

                            $error = false;
                            $status_code = 200;
                            $error_msg = 'Thank you for subscribing to Songwriter Sunday School!';

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
                            Notification::route('mail', 'admin@songwritersundayschool.com')
                                        ->notify(new SendFeedbackNotification($mail_data));

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

                            Notification::route('mail', $request->input('email'))
                                        ->notify(new SendFeedbackNotification($mail_data));


                            Auth::loginUsingId($user->id);
                            $user = Auth::user();
                            Session::put('user', $user);

                        } else { //if the payment is not successful
                            $error_msg = 'Problem occured when deducting the amount in the card.';

                            // remove the contact in infusionsoft if the payment is not successful
                            $post_method = 'DELETE';
                            $end_point = 'contacts/'.$inf_contact->id;
                            $this->send_request_infusion_soft($post_method, $end_point);
                        }
                    }
                }
            } else { //if there is error when adding the credit card
                $error_msg = $add_credit_card->message;

                // remove the contact in infusionsoft if there is no valid credit card to avoid duplicate entries
                $post_method = 'DELETE';
                $end_point = 'contacts/'.$inf_contact->id;
                $this->send_request_infusion_soft($post_method, $end_point);
            }
        } else { //if there is error when creating the contact
            $error_msg = $inf_contact->message;
        }

        return response()->json( ['error' => $error, 'message' => $error_msg ], $status_code );
    }

    private function send_request_infusion_soft ($post_method='GET', $end_point) {
        $_data = InfusionsoftToken::orderBy('id', 'desc')->first();
        $inf_token = new InfusionsoftToken();

        $curl_url = $inf_token->getApiEndPoint().$end_point;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $post_method);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$_data->access_token.' ';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        $return_data = json_decode($result);
        return $return_data;
    }

    private function post_to_infusion_soft ($post_data, $post_method, $post_protocol='') {
        //$this->get_access_token();
        $_data = InfusionsoftToken::orderBy('id', 'desc')->first();
        $inf_token = new InfusionsoftToken();

        if( trim($post_protocol) == '' )
            $curl_url = $inf_token->getApiEndPoint().$post_method;
        else
            $curl_url = $inf_token->getApiEndPoint($post_protocol).'?access_token='.$_data->access_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = array();

        if( trim($post_protocol) == '' ) {
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer '.$_data->access_token.' ';
        }
        else { $headers[] = 'Content-Type: text/xml'; }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        if( trim($post_protocol) == '' )
            $return_data = json_decode($result);
        else {
            $xml = <<<XML
$result
XML;
            $return_data = simplexml_load_string($xml);

        }
        return $return_data;
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

    private function get_access_token () {
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
    }


}
