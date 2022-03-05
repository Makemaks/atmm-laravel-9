<?php

namespace App\Http\Controllers\Api;

use App\Models\SheetMusic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SheetMusicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $request['task'] = 'viewing sheet musics';
      $this->save_api_activity_log($request);

      return $this->paginate($request, [
          'items' => (new SheetMusic())->where('is_public', 1),
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
      $sheetMusic = SheetMusic::findOrFail($id);

      if(!$sheetMusic->is_public) {
          return response()->json(['error' => 'Access Forbidden.'],403);
      }

      return $sheetMusic;
    }
}
