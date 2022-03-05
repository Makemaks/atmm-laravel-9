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

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscriptionpage(Request $request)
    {
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

        return view('m_checkoutpage.index', [
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

    public function secondstep(Request $request)
    {
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

        return view('m_checkoutpage.step', [
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

    public function downloadstep(Request $request)
    {
         return view('m_checkoutpage.download');
    }
}
