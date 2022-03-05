<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberMetrics extends Model
{
    protected $guarded = [];
    protected $table = 'subscriber_metrics';
    protected $fillable = ['song_id', 'user_id', 'ip_adress'];
}
