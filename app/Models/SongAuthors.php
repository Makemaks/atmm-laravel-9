<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongAuthors extends Model
{
    protected $guarded = [];
    protected $table = 'songs_authors';

    public function songs()
    {
        return $this->belongsToMany(
            'App\Models\MusicDetail',
            'songs_authors',
            'author_id',
            'music_detail_id'
        );
    }

}
