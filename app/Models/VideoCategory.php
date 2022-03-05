<?php

namespace App\Models;

use DB;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $guarded = [];

    public function videoDetails()
    {
        return $this->hasMany(VideoDetail::class, 'video_category_id', 'id')
                ->select('*',
                DB::raw("CASE
                    WHEN video_category_id = 2 THEN \"".url('/')."/img/white-video-placeholder.jpg\"
                    WHEN video_category_id = 3 THEN \"".url('/')."/img/white-video-placeholder.jpg\"
                    WHEN video_category_id = 4 THEN \"".url('/')."/img/dark-video-placeholder.jpg\"
                    WHEN video_category_id = 6 THEN \"".url('/')."/img/dark-video-placeholder.jpg\"
                END as img_placeholder"));
    }
}
