<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SheetMusic extends Model
{
    protected $guarded = [];
    protected $table = 'sheet_musics';

    public function formattedDate()
    {
        return $this->created_at->format('F d, Y g:i A');
    }

    public function saveImage($fileName)
    {
        $filePath = storage_path() . '/sheet_musics/images/' . $fileName;
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
        Storage::put("sheet_musics/{$this->id}/images/{$imageFilename}", file_get_contents($tmpName), 'public');
        $this->image = "sheet_musics/{$this->id}/images/{$imageFilename}";
        $this->image_width = $image->width();
        $this->image_height = $image->height();

        // resize uploaded image for the sheet music thumbnail
        $thumbnailFilename = "{$uniqID}_thumbnail{$imageExt}";
        $thumbnail = Image::make($filePath)->resize(275, 275, function($constraint) {
            $constraint->aspectRatio();
        });
        $tmpName = $tmp . $thumbnailFilename;
        $thumbnail->save($tmpName);
        Storage::put("sheet_musics/{$this->id}/images/{$thumbnailFilename}", file_get_contents($tmpName), 'public');
        $this->thumbnail = "sheet_musics/{$this->id}/images/{$thumbnailFilename}";
        $this->thumbnail_width = $thumbnail->width();
        $this->thumbnail_height = $thumbnail->height();

        $this->save();
    }

    public function saveFile($fileName)
    {
        $filePath = storage_path() . '/sheet_musics/' . $fileName;
        $s3Path = 'sheet_musics/'. $this->id . '/' .$fileName;
        Storage::put($s3Path, \fopen($filePath, 'r+'), 'public');
        $this->file = $s3Path;
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

    public function removeFile() {
        if($this->file) {
            Storage::delete($this->file);
        }
    }
}
