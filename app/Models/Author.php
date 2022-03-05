<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    public function musicDetails()
    {
        return $this->belongsToMany(
            'App\Models\MusicDetail',
            'songs_authors',
            'author_id',
            'music_detail_id'
        );
    }
}
