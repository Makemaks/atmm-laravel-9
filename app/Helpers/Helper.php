<?php

namespace App\Helpers;
use App\Jobs\SaveImages;
use App\BandAlbum;

class Helper
{
    protected $file;

    protected $bandAlbum;

    public function __construct(BandAlbum $bandAlbum, $file)
    {
        $this->file = $file;

        $this->bandAlbum = $bandAlbum;
    }

    public static function saveImage()
    {
        $filename = str_random() . '.' . $this->file->getClientOriginalExtension();
        Storage::disk('s3')->putFileAs('image/' . $this->bandAlbum->id, $this->file, $filename, 'public');
        $this->image = 'image/' . $this->id . '/' . $filename;
        $this->save();
    }
}