<?php

namespace App\Http\Controllers\Api;

use App\Models\Apploginhistory;
use Illuminate\Http\Request;

class ApploginhistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Apploginhistory  $apploginhistory
     * @return \Illuminate\Http\Response
     */
    public function show(Apploginhistory $apploginhistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Apploginhistory  $apploginhistory
     * @return \Illuminate\Http\Response
     */
    public function edit(Apploginhistory $apploginhistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Apploginhistory  $apploginhistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apploginhistory $apploginhistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apploginhistory  $apploginhistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apploginhistory $apploginhistory)
    {
        //
    }
}
