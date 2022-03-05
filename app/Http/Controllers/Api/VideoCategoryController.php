<?php

namespace App\Http\Controllers\Api;

use App\Models\VideoCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $request['task'] = 'viewing video categories';
      $this->save_api_activity_log($request);

      return $this->paginate($request, [
          'items' => (new VideoCategory),
          's_fields' => ['description'],
          'sortBy' => 'description',
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
      return VideoCategory::findOrFail($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAlbumSongs($id, Request $request)
    {
      $video_category = VideoCategory::findOrFail($id);

      return $this->paginate($request, [
            'items' => $video_category->videoDetails()->where('is_public', 1),
            's_fields' => ['title'],
            'sortBy' => 'title',
            'sortOrder' => 'ASC'
        ]);
    }
}
