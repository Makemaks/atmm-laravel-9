<?php

namespace App\Http\Controllers;

use App\VideoDetail;
use Illuminate\Http\Request;
use App\Helpers\StreamFile;

class VideoStreamController extends Controller
{
    public function streamVideo(Request $request)
    {
        try {
            $id = $request->id;
            $resolution = $request->resolution;
            $video = VideoDetail::findOrFail($id);

            if( $request->resolution == 480)
                $videopath = $video->video_480;
            elseif( $request->resolution == 720)
                $videopath = $video->video_720;
            elseif( $request->resolution == 1080)
                $videopath = $video->video_1080;
            elseif( $request->resolution == 'default')
                $videopath = $video->video_default;
            else 
                $videopath = $video->video;

            //$stream = new StreamFile($video->video);
            $stream = new StreamFile( $videopath);
            $stream->streamFile();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    
}
