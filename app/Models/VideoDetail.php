<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class VideoDetail extends Model
{
    protected $guarded = [];

    public function saveVideo($fileName)
    {

        $filePath = storage_path() . '/videos/' . $fileName;

        // for shell script identifier
        $this->file_name = $fileName;
        $this->file_size = filesize($filePath);
        $this->save();

        $s3Path = 'videos/'. $this->id . '/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->video = $s3Path;
        $this->uploaded_via = 'default';
        if($this->save()) {
            unlink($filePath);
        }

    }

    public function saveImage($fileName)
    {
        $filePath = storage_path() . '/videos/images/' . $fileName;
        $imageDetails = getimagesize($filePath);

        $imageExt = image_type_to_extension($imageDetails[2]);

        // set tmp dir
        $tmp = is_dir('C:/xampp/tmp/') ? 'C:/xampp/tmp/' : '/tmp/' ;
        // set uniqID for file name
        $uniqID = uniqid('', true);

        // resize uploaded image for the sheet video image
        $imageFilename = $uniqID . $imageExt;
        $image = Image::make($filePath)->resize(1280, 720, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $imageFilename;
        $image->save($tmpName);
        Storage::put("videos/{$this->id}/images/{$imageFilename}", file_get_contents($tmpName), 'public');
        $this->image = "videos/{$this->id}/images/{$imageFilename}";
        $this->image_width = $image->width();
        $this->image_height = $image->height();

        // resize uploaded image for the sheet video thumbnail
        $thumbnailFilename = "{$uniqID}_thumbnail{$imageExt}";
        $thumbnail = Image::make($filePath)->resize(275, 275, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $thumbnailFilename;
        $thumbnail->save($tmpName);
        Storage::put("videos/{$this->id}/images/{$thumbnailFilename}", file_get_contents($tmpName), 'public');
        $this->thumbnail = "videos/{$this->id}/images/{$thumbnailFilename}";
        $this->thumbnail_width = $thumbnail->width();
        $this->thumbnail_height = $thumbnail->height();

        $this->save();
    }

    public function videoCategory()
    {
        return $this->belongsTo(VideoCategory::class, 'video_category_id', 'id');
    }

    public function removeImage() {
        if($this->image) {
            Storage::delete($this->image);
        }

        if($this->thumbnail) {
            Storage::delete($this->thumbnail);
        }
    }

    public function removeVideo() {
        if($this->video) {
            $this->iscopiedinsecondserver = 0;
            $this->video_480 = '';
            $this->video_720 = '';
            $this->video_1080 = '';
            $this->video_default = '';
            $this->save();
            Storage::delete($this->video);
        }
    }
}
