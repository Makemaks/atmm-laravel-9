<?php

namespace App\Http\Controllers\Api;

use App\Models\AppSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AppSettings::orderBy('key', 'ASC')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $AppSettings = AppSettings::findOrFail($id);
        return $AppSettings;
    }

    /**
     * Updates the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $AppSettings = AppSettings::findOrFail($id);

        $AppSettings->value = $request->input('value');

        $AppSettings->save();

        return $AppSettings;
    }

    /**
     * Updates the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateVersion(Request $request)
    {
      $AppSettings = AppSettings::findOrFail('version');
      $AppSettings->value = $request->input('value');
      $AppSettings->save();
      return $AppSettings;
    }
}
