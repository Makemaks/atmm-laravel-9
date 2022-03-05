<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class MusicDetailsBandAlbum extends Model
{
    protected $guarded = [];
    protected $table = 'music_details_band_albums';
    protected $fillable = ['album_music_id', 'band_album_id', 'music_detail_id', 'track_sequence'];

    public function scopeGetMusics($query, $band_album_id)
    {
        return $query->where('band_album_id', $band_album_id);
    }

}
