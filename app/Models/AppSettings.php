<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{

    protected $primaryKey = 'key'; // or null

    public $incrementing = false;

    protected $guarded = [];

}
