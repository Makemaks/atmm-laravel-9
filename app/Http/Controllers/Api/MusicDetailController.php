<?php

namespace App\Http\Controllers\Api;

use App\Models\MusicDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MusicDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $request['task'] = 'viewing songs';
      $this->save_api_activity_log($request);

      return $this->paginate($request, [
          'items' => (new MusicDetail())->where('is_public', 1),
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
        $musicDetail = MusicDetail::findOrFail($id);

        if(!$musicDetail->is_public) {
            return response()->json(['error' => 'Access Forbidden.'],403);
        }

        return $musicDetail;
    }
}
