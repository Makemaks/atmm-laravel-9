<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $guarded = [];

    public function musicDetails()
    {
        return $this->belongsToMany(
            'App\Models\MusicDetail',
            'songs_artists',
            'artist_id',
            'music_detail_id'
        );
    }
}
