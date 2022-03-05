<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Userscancelled;
use App\Models\Nmipayments;
use App\Models\Nmitransactions;
use App\Models\Products;
use App\Models\InfusionsoftToken;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFeedbackNotification;

class NmiUserSettingsController extends Controller
{
  /**
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      try {
          if($this->isCurrentUserAdmin())
              abort(403);

          $user = Auth::user();
          $nmitransactions = Nmitransactions::select('transaction_id', 'order_id', 'email',
                                                     'original_transaction_id', 'source', 'success', 'condition',
                                                     'date', 'date_formatted')
                              ->where('email', $user->email)
                              //->where('source', 'recurring')
                              //->where('user_id', $user->id)
                              ->orderBy('transaction_id', 'desc')->first();

          $allnmitransactions = Nmitransactions::where('email', $user->email)
                                        //->where('source', 'recurring')
                                        ->orderBy('transaction_id', 'desc')->get();

          if(!$nmitransactions) {
            // user registered in infusionsoft, not sure for now how to cancel those user
            $nmitransactions = new \stdClass();
            $nmitransactions->status = '';
            $nmitransactions->subscription_id = '';
            $nmitransactions->order_id = '';
            $nmitransactions->date_formatted = '';
            $nmitransactions->source = '';
            //$nmitransactions->registeredvia = 'Infusionsoft';
            $nmitransactions->registeredvia = 'NMI';
            $userscancelled = '';
          } else {

            $user = Auth::user();
            $nmipayments = Nmipayments::select('id', 'subscription_id')
                                ->where('user_id', $user->id)
                                ->orderBy('id', 'desc')->first();

            //$nmitransactions = new \stdClass();
            $nmitransactions->status = $nmitransactions->success ? 'Success' : 'Failed';
            //$nmitransactions->subscription_id = $nmitransactions->original_transaction_id;
            $nmitransactions->subscription_id = $nmipayments->subscription_id;
            $nmitransactions->registeredvia = 'NMI';

            $userscancelled = Userscancelled::select('id')
                                ->where('user_id', $user->id)
                                ->where('subscription_id', $nmipayments->subscription_id)
                                ->orderBy('id', 'desc')->first();
          }

          return view('account_page.nmiusersettings', [
              'nmitransactions' => $nmitransactions,
              'allnmitransactions' => $allnmitransactions,
              'userscancelled' => $userscancelled,
              'user' => $user,
          ]);

      } catch (\Throwable $th) {
          throw $th;
      }

  }

  public function cancelSubscription(Request $request)
  {
      try {
          $user = Auth::user();
          $nmipayments = Nmipayments::select('id', 'subscription_id')
                              ->where('user_id', $user->id)
                              ->orderBy('id', 'desc')->first();

          $nmipayment_id = $nmipayments->id;

          $api_method = 'POST';
          $end_point = '/api/transact.php';
          $api_data = array();
          $api_data['recurring'] = 'delete_subscription';
          $api_data['subscription_id'] =  $request->subscription_id;
          $nmi_data = $this->api_send_request_to_nmi($api_method, $end_point, $api_data);

          $data = explode("&",$nmi_data);
          for($i=0;$i<count($data);$i++) {
              $rdata = explode("=",$data[$i]);
              $responses[$rdata[0]] = $rdata[1];
          }
          $error_msg = '';
          $error = true;
          $status_code = 403;
          if(strtolower( trim($responses['responsetext']) ) == 'recurring transaction deleted') {
            /*
            $nmi_payments =  Nmipayments::findOrFail($nmipayment_id);
            $nmi_payments->status = 'Cancelled';
            $nmi_payments->reasong_to_cancel = trim( $request->input('reason_to_stopped') );
            $nmi_payments->save();
            */

            $userscancelled = new Userscancelled();
            $userscancelled->user_id = $user->id;
            $userscancelled->email = $user->email;
            $userscancelled->reason_to_stop = trim( $request->input('reason_to_stopped') );
            $userscancelled->subscription_id = $request->subscription_id;
            $userscancelled->original_transaction_id = $request->subscription_id;
            $userscancelled->save();

            /* the logic code below dont need or there's no need to update the user status
               since there is cronjob that will check the latest payment and will update the status

            //change the original email so that user can signup again in case want to come back
            $user =  User::findOrFail($user->id);
            $user->subscription_status = 'CANCELLED';
            $user->email = $user->email.'_CANCELLED_'.time();
            $user->save();
            */

            $error = false;
            $status_code = 200;
            $error_msg = 'Your Subscription successfully cancelled!';


            // notify admin
            $mail_data = array();
            $mail_data['email'] = 'songwritersundayschool@gmail.com';
            $mail_data['subject'] = 'User Cancelled a Subscription';
            $mail_data['message'] = '
                            Hey Dude,
                            <br><br>
                            Just want to notify you that there is a user who just cancelled there subscription on AllThingsMichaelMcLean.com

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
                        Notification::route('mail', 'admin@songwriter.com')->notify(new SendFeedbackNotification($mail_data));



                        // notify end-user
                        $mail_data = array();
                        $mail_data['email'] = 'songwritersundayschool@gmail.com';
                        $mail_data['subject'] = 'You have successfully cancelled your subscription in AllThingsMichaelMcLean.com ';
                        $mail_data['message'] = '
                                        Hi '.$user->name.',
                                        <br><br>
                                        Your account has been cancelled in AllThingsMichaelMcLean.com
                                        <br><br>
                                        Please contact us if youre not the one who made this action. <br>
                                        <br><br>
                                        Thanks,<br>
                                    ';
                        Notification::route('mail', $user->email)->notify(new SendFeedbackNotification($mail_data));



          } else {

            if( isset($responses['responsetext']) && trim($responses['responsetext']) != '' ) {
              $error_msg = $responses['responsetext'];
            } else {
              $error_msg = 'Problem occured when cancelling a subscription in NMI.';
            }

            $status_code = 422;
            $errors = new \stdClass;
            $errors->nmimessage = $error_msg;
            return response()->json( ['errors' => $errors, 'message' => 'The given data was invalid.' ], 422 );

          }

          return response()->json( ['error' => $error, 'message' => $error_msg ], $status_code );
      } catch (\Throwable $th) {
          throw $th;
      }
  }


  /**
   *
   * @return \Illuminate\Http\Response
   */
  public function selectPayment(Request $request)
  {
    try {
      $products = Products::select('product_name','product_price','sales_tax')->get();
      return view('select_payment.nmi', ['products' => $products] );
    } catch (\Throwable $th) {
        throw $th;
    }
  }

  public function subscriptionpage (Request $request) {

      if( trim($request->periodic) != 'monthly' && trim($request->periodic) != 'yearly') {
          return Redirect::to('select_payment');
      }

      $product = Products::select('product_name','product_price','sales_tax')->where('product_name','=',$request->periodic)->orderBy('id', 'desc')->first();

      $price = $product->product_price;
      $tax = $product->sales_tax;
      $productname = ucfirst($product->product_name).' Subscription';
      $product_short_desc = ucfirst($product->product_name).' Subscription';

      $total = $price + $tax;
      $formatted_price = number_format($price,2);
      $formatted_total = number_format($total,2);

      $inf_token = new InfusionsoftToken();
      return view('select_payment.subscription_nmi', [
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


}
