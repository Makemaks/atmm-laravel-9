<?php

namespace App\Http\Controllers\Web;

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
use CountryState;
use Auth;
use Session;

class InfusionsoftController extends Controller
{

    public function subscriptionpage (Request $request) {

        if( trim($request->periodic) != 'monthly' && trim($request->periodic) != 'yearly') {
            return Redirect::to('select_payment');
        }

        $response = $this->getInfusionsoftProducts();
        $products = $response['data'];
        $price = 0;
        $tax = 0;
        $productname = '';
        $product_short_desc = '';
        if( count($products) > 0 ) {
          foreach($products as $ind => $product) {
            $product_name = trim(strtolower(str_replace('Subscription', '', $product->product_name)));
            if (isset($product->subscription_plans) && count($product->subscription_plans) > 0) {
              foreach ($product->subscription_plans as $subscription_plan) {
                if ( trim($request->periodic) == 'monthly' && $product_name == 'monthly' && $subscription_plan->active == 1 && $subscription_plan->is_hide == 0) {
                  $price = $subscription_plan->plan_price;
                  $productname = $product->product_name;
                  $product_short_desc = $product->product_short_desc;
                }
                if ( trim($request->periodic) == 'yearly' && $product_name == 'yearly' && $subscription_plan->active == 1 && $subscription_plan->is_hide == 0) {
                  $price = $subscription_plan->plan_price;
                  $productname = $product->product_name;
                  $product_short_desc = $product->product_short_desc;
                }
              }
            }
          }
        }


        if(trim($productname) == '') {
          return Redirect::to('select_payment');
        }

        $total = $price + $tax;
        $formatted_price = number_format($price,2);
        $formatted_total = number_format($total,2);

        $inf_token = new InfusionsoftToken();
        return view('select_payment.subscription', [
            'countries' => $inf_token->getCountries(),
            //'countries' => CountryState::getCountries(),
            'periodic' => $request->periodic,
            'price' => $formatted_price,
            'tax' => $tax,
            'total' => $formatted_total,
            'productname' => $productname,
            'product_short_desc' => $product_short_desc,
        ]);

    }

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

                  $user = new User();
                  $user->name = trim( $request->input('fname') ).' '.trim( $request->input('lname') );
                  $user->email = trim( $request->input('email') );
                  $user->password = bcrypt( trim( $request->input('password') ) );
                  $user->credit_card_id = $add_credit_card->id;
                  $user->subscription_type_id = $selected_product_id;
                  $user->subscription_status = 'PAID'; // harcoded PAID status but belong to trial table for enabling login purposes
                  $user->save();

                  $inf_trial = new InfusionsoftTrialLog();
                  $inf_trial->user_id = $user->id;
                  $inf_trial->contact_id = $inf_contact->id;
                  $inf_trial->creditcard_id = $add_credit_card->id;
                  $inf_trial->product_id = $selected_product_id;
                  $inf_trial->product_label = $periodic;
                  $inf_trial->subscription_plan_id = $selected_subscription_id;
                  $inf_trial->save();

                  $this->notifyAdminSubsriber($request);

                  $error = false;
                  $status_code = 200;
                  $error_msg = 'Thank you for subscribing to Songwriter Sunday School!';

                  Auth::loginUsingId($user->id);
                  $user = Auth::user();
                  Session::put('user', $user);
                  Session::put('new_subscriber_user', 1); // for FB pixel Subscribe

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
      //Notification::route('mail', 'admin@songwritersundayschool.com')->notify(new SendFeedbackNotification($mail_data));

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



}
