<?php

namespace App\Http\Controllers\Api;

use App\Models\Instrumental;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instrumental\Store;
use DB;

class InstrumentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $request['task'] = 'viewing karaokes';
      $this->save_api_activity_log($request);

      return $this->paginate($request, [
          'items' => (new Instrumental())->select('*',
            DB::raw("\"".url('/')."/img/white-video-placeholder.jpg\" as high_key_img_placeholder"),
            DB::raw("\"".url('/')."/img/dark-video-placeholder.jpg\" as low_key_img_placeholder")
          )->where('is_public', 1),
          's_fields' => ['title'],
          'sortBy' => 'title',
          'sortOrder' => 'ASC'
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $instrumental = Instrumental::findOrFail($id);

      if(!$instrumental->is_public) {
         return response()->json(['error' => 'Access Forbidden.'],403);
      }

      $instrumental->high_key_img_placeholder = url('/') . '/img/white-video-placeholder.jpg';
      $instrumental->low_key_img_placeholder = url('/') . '/img/dark-video-placeholder.jpg';

      return $instrumental;
    }
}
