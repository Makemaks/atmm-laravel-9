<?php

namespace App\Models;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Nmipayments extends Model
{
  protected $end_point = 'https://merchantsolutionservices.transactiongateway.com';
  protected $access_token = '';
  protected $api_data = array();

  function __construct() {

  }

  public function getApiEndPoint() {
    return $this->end_point;
  }

  public function getAccesToken() {
    $this->access_token = Config::get('nmi.nmi_api_key');
    return $this->access_token;
  }

  private function setBilling(Request $request) {
    $this->api_data['firstname'] = trim( $request->input('fname') );
    $this->api_data['lastname'] = trim( $request->input('lname') );
    $this->api_data['company'] = '';
    $this->api_data['address1']  = trim( $request->input('addrline1') );
    $this->api_data['address2']  = trim( $request->input('addrline2') );
    $this->api_data['city']  = trim( $request->input('city') );
    $this->api_data['state']  = trim( $request->input('state') );
    $this->api_data['zip']  = trim( $request->input('zipcode') );
    $this->api_data['country']  = trim( $request->input('country') );
    $this->api_data['phone']  = trim( $request->input('phone') );
    $this->api_data['fax']  = '';
    $this->api_data['email']  = trim( $request->input('email') );
    $this->api_data['website']  = '';
  }

  private function setShipping(Request $request) {
    $this->api_data['shipping_firstname'] = trim( $request->input('fname') );
    $this->api_data['shipping_lastname'] = trim( $request->input('lname') );
    $this->api_data['shipping_company'] = '';
    $this->api_data['shipping_address1']  = trim( $request->input('addrline1') );
    $this->api_data['shipping_address2']  = trim( $request->input('addrline2') );
    $this->api_data['shipping_city']  = trim( $request->input('city') );
    $this->api_data['shipping_state']  = trim( $request->input('state') );
    $this->api_data['shipping_zip']  = trim( $request->input('zipcode') );
    $this->api_data['shipping_country']  = trim( $request->input('country') );
    $this->api_data['shipping_email']  = trim( $request->input('email') );
  }

  private function setOrder(Request $request) {
    $product_name = trim( $request->input('periodic') );
    $products = Products::select('product_price','sales_tax')->where('product_name','=',$product_name)->orderBy('id', 'desc')->first();

    $current_time = time();
    $this->api_data['orderid'] = trim( $request->input('periodic') ) . '-' . $current_time;
    $this->api_data['orderdescription'] = trim( $request->input('periodic') );
    $this->api_data['tax'] = number_format($products->sales_tax,2,".","");
    $this->api_data['shipping']  = '';
    $this->api_data['ponumber']  = 'PO-'.$current_time;
    $this->api_data['ipaddress']  = request()->ip();
  }

  private function setCreditCard(Request $request) {
    $product_name = trim( $request->input('periodic') );
    $products = Products::select('product_price','sales_tax')->where('product_name','=',$product_name)->orderBy('id', 'desc')->first();

    $plan_amount = $products->product_price + $products->sales_tax;
    $this->api_data['amount'] = number_format($plan_amount,2,".","");
    $this->api_data['ccnumber'] = trim( $request->input('cc_number') );
    $this->api_data['ccexp'] = trim( $request->input('expirationMonth') ).trim( $request->input('expirationYear') );
  }

  private function setSaleType(Request $request, $type='sale') {
    // sale, auth, capture, void, refund, credit, validate, update
    $this->api_data['type'] = $type;
  }

  private function setRecurring(Request $request) {
    $product_name = trim( $request->input('periodic') );
    $products = Products::select('product_price','sales_tax')->where('product_name','=',$product_name)->orderBy('id', 'desc')->first();

    $date_start = strtotime(date("Y/m/d"));
    $date_start = date("Y-m-d", strtotime("+1 month", $date_start));
    //$date_start = date("Y-m-d", strtotime("+1 month", $date_start));
    //$date_start = date("Y-m-d", strtotime("+14 days", $date_start));
    //$date_start = date("Y-m-d", strtotime("+1 days", $date_start)); // for deugging
    //$date_start = date("Y-m-d");
    $day_of_month = date("d", strtotime($date_start));
    $date_start = str_replace('-','',$date_start);
    //$day_of_month = date("d");

    /*
    $time = strtotime(date("Y/m/d"));
    $date_start = date("Y-m-d", strtotime("+1 day", $time));
    $date_start = str_replace('-','',$date_start);
    */

    //$this->api_data['plan_id'] = 1;




    $this->api_data['recurring'] = 'add_subscription';
    $this->api_data['plan_payments'] = 0;
    $plan_amount = $products->product_price + $products->sales_tax;
    $this->api_data['plan_amount'] = number_format($plan_amount,2,".","");
    $this->api_data['month_frequency'] = 1;
    $this->api_data['day_of_month'] = $day_of_month;
    $this->api_data['start_date'] = $date_start;
    //$this->api_data['ccnumber'] = trim( $request->input('cc_number') );
    //$this->api_data['ccexp'] = trim( $request->input('expirationMonth') ).trim( $request->input('expirationYear') );




    // from Credit Card Number
    //$this->api_data['account_type'] = 'savings';
    $this->api_data['customer_receipt'] = true;
    //$this->api_data['source_transaction_id'] = '';
  }

  public function getAPIData(Request $request) {
    $this->setBilling($request);
    $this->setShipping($request);
    $this->setOrder($request);
    $this->setCreditCard($request);
    $this->setSaleType($request);
    $this->setRecurring($request);
    return $this->api_data;
  }

  public function user()
  {
      return $this->belongsTo(User::class, 'user_id', 'id');
  }


}
