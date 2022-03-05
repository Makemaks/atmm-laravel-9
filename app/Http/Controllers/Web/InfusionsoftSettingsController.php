<?php

namespace App\Http\Controllers\Web;

use App\Models\InfusionsoftSettings;
use App\Models\InfusionsoftToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Batch;

class InfusionsoftSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      try {

        if(!$this->isCurrentUserAdmin()) {
            abort(404);
        } else {
          if($request->ajax()) {
            $response = $this->getInfusionsoftProducts();
            return response()->json($response, 200);
          } else {
            return view('infusionsoft_settings.admin.index');
          }
        }

      } catch (\Throwable $th) {
          throw $th;
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hideShowInfusionSoftProduct(Request $request)
    {
      if(!$this->isCurrentUserAdmin()) {
        return response()->json('error', 404);
      } else {
        $id = $request->id;
        $infset = InfusionsoftSettings::findOrFail($id);
        if( $infset->is_hide == 0 ) {
          $infset->is_hide = 1;
        } else {
          $infset->is_hide = 0;
        }
        $infset->save();

        $response = $this->getInfusionsoftProducts();
        return response()->json($response, 200);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveSalesTax(Request $request)
    {
      if(!$this->isCurrentUserAdmin()) {
        return response()->json('error', 404);
      } else {
        $id = $request->id;
        $infset = InfusionsoftSettings::findOrFail($id);
        $infset->sales_tax = $request->salestax;
        $infset->save();

        $response = $this->getInfusionsoftProducts();
        return response()->json($response, 200);
      }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPromotions(Request $request)
    {
      try {

        if(!$this->isCurrentUserAdmin()) {
            abort(404);
        } else {

          $api_method = 'POST';
          $end_point = '';
          $xml_data = $this->get_promotion_data();
          $result = $this->api_send_request_to_infusion_soft($api_method, $end_point, 'xml-rpc',$xml_data);

          if($request->ajax()) {
            $data = array();
            if( isset($result->params->param->value) ) {
              $data = explode(',',$result->params->param->value);
            }
            return response()->json($data);
          } else {
            return view('infusionsoft_settings.admin.promotions');
          }
        }

      } catch (\Throwable $th) {
          throw $th;
      }
    }

    private function get_promotion_data()
    {
        $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
        <methodCall>
          <methodName>DataService.getAppSetting</methodName>
          <params>
            <param><value><string>infusionsoft_token_value_here</string></value></param>
            <param><value><string>Product</string></value></param>
            <param><value><string>optionpromocode</string></value></param>
          </params>
        </methodCall>';
        return $xml_data;
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function selectPayment(Request $request)
    {
      try {
        $products = $this->getInfusionsoftProducts();
        return view('select_payment.index', ['products' => $products['data']]);
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
     * @param  \App\InfusionsoftSettings  $infusionsoftSettings
     * @return \Illuminate\Http\Response
     */
    public function show(InfusionsoftSettings $infusionsoftSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InfusionsoftSettings  $infusionsoftSettings
     * @return \Illuminate\Http\Response
     */
    public function edit(InfusionsoftSettings $infusionsoftSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InfusionsoftSettings  $infusionsoftSettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InfusionsoftSettings $infusionsoftSettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InfusionsoftSettings  $infusionsoftSettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(InfusionsoftSettings $infusionsoftSettings)
    {
        //
    }
}
