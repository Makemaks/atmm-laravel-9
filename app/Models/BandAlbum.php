<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class BandAlbum extends Model
{
    protected $guarded = [];

    public function saveImage($fileName)
    {
        $filePath = storage_path() . '/albums/images/' . $fileName;
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
        Storage::put("albums/{$this->id}/images/{$imageFilename}", file_get_contents($tmpName), 'public');
        $this->image = "albums/{$this->id}/images/{$imageFilename}";

        // resize uploaded image for the sheet music thumbnail
        $thumbnailFilename = "{$uniqID}_thumbnail{$imageExt}";
        $thumbnail = Image::make($filePath)->resize(275, 275, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $thumbnailFilename;
        $thumbnail->save($tmpName);
        Storage::put("albums/{$this->id}/images/{$thumbnailFilename}", file_get_contents($tmpName), 'public');
        $this->thumbnail = "albums/{$this->id}/images/{$thumbnailFilename}";

        $this->save();
    }

    public function saveLiner($fileName)
    {
        $filePath = storage_path() . '/album_liners/' . $fileName;
        $s3Path = 'album_liners/'. $this->id . '/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->liner = $s3Path;
        if($this->save()) {
            unlink($filePath);
        }
    }

    public function removeImage() {
        if($this->image) {
            Storage::delete($this->image);
        }

        if($this->thumbnail) {
            Storage::delete($this->thumbnail);
        }
    }

    public function removeLiner() {
        if($this->liner) {
            Storage::delete($this->liner);
        }
    }

    public function musicDetails($public = 1)
    {
        $model = $this->belongsToMany(
            'App\Models\MusicDetail',
            'music_details_band_albums',
            'band_album_id',
            'music_detail_id'
        )
        ->withPivot('track_sequence','album_music_id');

        if($public == 1) {
            $model = $model->where('is_public', 1);
        }

        return $model;
        // commented out so the order by can be controlled outside this method.
        //->orderBy('track_sequence','asc');
    }

    public function songLists()
    {
        $model = $this->belongsToMany(
            'App\Models\MusicDetail',
            'music_details_band_albums',
            'band_album_id',
            'music_detail_id'
        )
        ->select('id','title')
        ->withPivot('track_sequence','album_music_id')
        ->orderBy('track_sequence','asc');

        return $model;
    }

}
