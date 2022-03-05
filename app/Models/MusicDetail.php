<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class MusicDetail extends Model
{
    protected $guarded = [];

    public function bandAlbums()
    {
        return $this->belongsToMany(
            'App\Models\BandAlbum',
            'music_details_band_albums',
            'music_detail_id',
            'band_album_id'
        );
    }

    public function artists()
    {
        return $this->belongsToMany(
            'App\Models\Artist',
            'songs_artists',
            'music_detail_id',
            'artist_id'
        );
    }

    public function authors()
    {
        return $this->belongsToMany(
            'App\Models\Author',
            'songs_authors',
            'music_detail_id',
            'author_id'
        );
    }

    public function saveAudio($fileName)
    {
        //$filePath = storage_path() . '/uploads/' . $fileName;
        $filePath = storage_path() . '/audios/' . $fileName;
        $s3Path = 'audios/'. $this->id . '/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->audio = $s3Path;
        if($this->save()) {
            unlink($filePath);
        }
    }

    public function saveImage($fileName)
    {
        $filePath = storage_path() . '/audios/images/' . $fileName;
        $imageDetails = getimagesize($filePath);

        $imageExt = image_type_to_extension($imageDetails[2]);

        // set tmp dir
        $tmp = is_dir('C:/xampp/tmp/') ? 'C:/xampp/tmp/' : '/tmp/' ;
        // set uniqID for file name
        $uniqID = uniqid('', true);

        // resize uploaded image for the sheet music image
        $imageFilename = $uniqID . $imageExt;
        $image = Image::make($filePath)->resize(1280, 720, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $imageFilename;
        $image->save($tmpName);
        Storage::put("audios/{$this->id}/images/{$imageFilename}", file_get_contents($tmpName), 'public');
        $this->image = "audios/{$this->id}/images/{$imageFilename}";
        $this->image_width = $image->width();
        $this->image_height = $image->height();

        // resize uploaded image for the sheet music thumbnail
        $thumbnailFilename = "{$uniqID}_thumbnail{$imageExt}";
        $thumbnail = Image::make($filePath)->resize(275, 275, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $thumbnailFilename;
        $thumbnail->save($tmpName);
        Storage::put("audios/{$this->id}/images/{$thumbnailFilename}", file_get_contents($tmpName), 'public');
        $this->thumbnail = "audios/{$this->id}/images/{$thumbnailFilename}";
        $this->thumbnail_width = $thumbnail->width();
        $this->thumbnail_height = $thumbnail->height();

        $this->save();
    }

    public function removeImage() {
        if($this->image) {
            Storage::delete($this->image);
        }

        if($this->thumbnail) {
            Storage::delete($this->thumbnail);
        }
    }

    public function removeAudio() {
        if($this->audio) {
            Storage::delete($this->audio);
        }
    }

}
