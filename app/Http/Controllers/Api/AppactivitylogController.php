<?php

namespace App\Http\Controllers\Api;

use App\Models\Appactivitylog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppactivitylogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $result = $this->save_api_activity_log($request);
      return response()->json(['result' => $result, 'data' => $result ],200);

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
      $result = $this->save_api_activity_log($request);
      return response()->json(['result' => $result, 'data' => $result ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function show(Appactivitylog $appactivitylog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function edit(Appactivitylog $appactivitylog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appactivitylog $appactivitylog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appactivitylog  $appactivitylog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appactivitylog $appactivitylog)
    {
        //
    }
}
