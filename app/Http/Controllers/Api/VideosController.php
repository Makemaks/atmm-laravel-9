<?php

namespace App\Http\Controllers\Api;

use App\Models\VideoDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return VideoDetail::select('id','video','file_name')->where('iscopiedinsecondserver','=',0)->orderBy('id', 'desc')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getVideosCopiedOnOtherServer(Request $request)
    {
        return VideoDetail::select('id','video','file_name')->where('iscopiedinsecondserver','=',1)->orderBy('id', 'asc')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getSingleVideoCopiedOnOtherServer(Request $request)
    {
        return VideoDetail::select('id','video','file_name','video_480','video_720','video_1080','video_default')
                            ->where('iscopiedinsecondserver', '=', '1')
                            ->where(function ($query) {
                                $query->where('video_480', '=', '')
                                      ->orWhere('video_720', '=', '')
                                      ->orWhere('video_1080', '=', '')
                                      ->orWhere('video_default', '=', '')
                                      ->orWhere('video_480', '=', NULL)
                                      ->orWhere('video_720', '=', NULL)
                                      ->orWhere('video_1080', '=', NULL)
                                      ->orWhere('video_default', '=', NULL);
                            })
                            ->orderBy('id', 'asc')->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getCompletedProcessVideos(Request $request)
    {
        return VideoDetail::select('id','video','file_name','video_480','video_720','video_1080','video_default')
                            ->where('iscopiedinsecondserver', '=', '1')
                            ->where('video_480', '<>', '')
                            ->where(function ($query) {
                                $query->orWhere('video_480', '<>', NULL);
                            })
                            ->where('video_720', '<>', '')
                            ->where(function ($query) {
                                $query->orWhere('video_720', '<>', NULL);
                            })
                            ->where('video_1080', '<>', '')
                            ->where(function ($query) {
                                $query->orWhere('video_1080', '<>', NULL);
                            })
                            ->where('video_default', '<>', '')
                            ->where(function ($query) {
                                $query->orWhere('video_default', '<>', NULL);
                            })
                            ->orderBy('id', 'asc')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VideoDetail::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $video_detail = VideoDetail::findOrFail($id);

        /*************************************************
            For cron job update from other server
            need to implement this way, updating of this column is done individually, depends on the server performance or data might send individually
        ***************************************************/
        if( $request->has('iscopiedinsecondserver') )
            $video_detail->iscopiedinsecondserver = 1;
        if( $request->has('video_480') )
            $video_detail->video_480 = $request->video_480;
        if( $request->has('video_720') )
            $video_detail->video_720 = $request->video_720;
        if( $request->has('video_1080') )
            $video_detail->video_1080 = $request->video_1080;
        if( $request->has('video_default') ) {
            $video_detail->video_default = $request->video_default;
            $video_detail->video = $request->video_default;
        }

        $result = $video_detail->save();
        return response()->json( ['success' => $result] );
    }

}
