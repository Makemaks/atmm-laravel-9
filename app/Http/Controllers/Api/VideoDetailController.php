<?php

namespace App\Http\Controllers\Api;

use App\Models\VideoDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class VideoDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $request['task'] = 'viewing videos';
      $this->save_api_activity_log($request);

      return $this->paginate($request, [
          'items' => (new VideoDetail())->select('*',
            DB::raw("CASE
                WHEN video_category_id = 2 THEN \"".url('/')."/img/white-video-placeholder.jpg\"
                WHEN video_category_id = 3 THEN \"".url('/')."/img/white-video-placeholder.jpg\"
                WHEN video_category_id = 4 THEN \"".url('/')."/img/dark-video-placeholder.jpg\"
                WHEN video_category_id = 6 THEN \"".url('/')."/img/dark-video-placeholder.jpg\"
            END as img_placeholder"))->with('videoCategory')
            ->where('is_public', 1),
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

        $this->save_api_activity_log($request);

        $video = VideoDetail::findOrFail($id);

        if(!$video->is_public) {
            return response()->json(['error' => 'Access Forbidden.'],403);
        }

        switch ($video->video_category_id) {
            case 2:
            case 3:
                $video->img_placeholder = url('/') . '/img/white-video-placeholder.jpg';
                break;

            case 4:
            case 6:
                $video->img_placeholder = url('/') . '/img/dark-video-placeholder.jpg';
                break;
        }

        return $video;
    }

}
