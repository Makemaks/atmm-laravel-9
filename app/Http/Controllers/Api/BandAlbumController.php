<?php

namespace App\Http\Controllers\Api;

use App\Models\BandAlbum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BandAlbum\Store;
use App\Http\Requests\BandAlbum\Update;
use App\Transformers\BandAlbumTransformer;

class BandAlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $request['task'] = 'viewing albums';
      $this->save_api_activity_log($request);

      return $this->paginate($request, [
          'items' => (new BandAlbum)->where('is_public', 1),
          's_fields' => ['album'],
          'sortBy' => 'release_date',
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
      $bandAlbum = BandAlbum::findOrFail($id);

      if(!$bandAlbum->is_public) {
        return response()->json(['error' => 'Access Forbidden.'],403);
      }

      return $bandAlbum;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAlbumSongs($id, Request $request)
    {
      $band_album = BandAlbum::with(['musicDetails' => function($query) {
            $query->orderBy('music_details_band_albums.track_sequence', 'ASC');
            //$query->orderBy('title', 'asc');
        }])->findOrFail($id);

      return response()->json(['current_page' => 1, 'data' => $band_album->musicDetails ],200);

      /*
      return $this->paginate($request, [
            'items' => $band_album,
            's_fields' => ['album'],
            'sortBy' => 'album',
            'sortOrder' => 'ASC'
        ]);
      */

    }
}
