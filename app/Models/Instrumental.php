<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Instrumental extends Model
{
    protected $guarded = [];

    public function saveHighKeyAudio($fileName)
    {
        $filePath = storage_path() . '/instrumentals/' . $fileName;
        $s3Path = 'instrumentals/'. $this->id . '/audios_high_key/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->high_key_audio = $s3Path;
        if($this->save()) {
            unlink($filePath);
        }
    }

    public function saveLowKeyAudio($fileName)
    {
        $filePath = storage_path() . '/instrumentals/' . $fileName;
        $s3Path = 'instrumentals/'. $this->id . '/audios_low_key/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->low_key_audio = $s3Path;
        if($this->save()) {
            unlink($filePath);
        }
    }

    public function saveHighKeyVideo($fileName)
    {
        $filePath = storage_path() . '/instrumentals/' . $fileName;
        $s3Path = 'instrumentals/'. $this->id . '/videos_high_key/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->high_key_video = $s3Path;
        if($this->save()) {
            unlink($filePath);
        }
    }

    public function saveLowKeyVideo($fileName)
    {
        $filePath = storage_path() . '/instrumentals/' . $fileName;
        $s3Path = 'instrumentals/'. $this->id . '/videos_low_key/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->low_key_video = $s3Path;
        if($this->save()) {
            unlink($filePath);
        }
    }

    public function formattedDate()
    {
        return $this->created_at->format('F d, Y g:i A');
    }

    public function saveImage($fileName)
    {
        $filePath = storage_path() . '/instrumentals/images/' . $fileName;
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
        Storage::put("instrumentals/{$this->id}/images/{$imageFilename}", file_get_contents($tmpName), 'public');
        $this->image = "instrumentals/{$this->id}/images/{$imageFilename}";
        $this->image_width = $image->width();
        $this->image_height = $image->height();

        // resize uploaded image for the sheet music thumbnail
        $thumbnailFilename = "{$uniqID}_thumbnail{$imageExt}";
        $thumbnail = Image::make($filePath)->resize(275, 275, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $thumbnailFilename;
        $thumbnail->save($tmpName);
        Storage::put("instrumentals/{$this->id}/images/{$thumbnailFilename}", file_get_contents($tmpName), 'public');
        $this->thumbnail = "instrumentals/{$this->id}/images/{$thumbnailFilename}";
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

    public function removeHighKeyAudio() {
        if($this->high_key_audio) {
            Storage::delete($this->high_key_audio);
        }
    }

    public function removeLowKeyAudio()
    {
        if($this->low_key_audio) {
            Storage::delete($this->low_key_audio);
        }
    }

    public function removeHighKeyVideo()
    {
        if($this->high_key_video) {
            Storage::delete($this->high_key_video);
        }
    }

    public function removeLowKeyVideo()
    {
        if($this->low_key_video) {
            Storage::delete($this->low_key_video);
        }
    }
}
