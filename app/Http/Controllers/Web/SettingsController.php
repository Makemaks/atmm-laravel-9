<?php

namespace App\Http\Controllers\Web;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
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
          $settings = Settings::select('value')->where('key','=','trial_days')->orderBy('created_at', 'desc')->limit(1)->get();
          $days_trial = $settings[0]->value;
          return view('settings.admin.index', ['days_trial' => $days_trial]);
        }

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
    public function store(Request $request, Settings $settings)
    {
      if(!$this->isCurrentUserAdmin())
          return response()->json('error', 404);

      $id = 'trial_days';
      $settings = Settings::findOrFail($id);
      $settings->value = $request->days_trial;
      $settings->save();
      return response()->json('success', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(Settings $settings)
    {
        if(!$this->isCurrentUserAdmin())
          return response()->json('error', 404);

        $id = 'trial_days';
        $settings = Settings::findOrFail($id);
        return $settings;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settings $settings)
    {
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Settings $settings)
    {
        //
    }
}
