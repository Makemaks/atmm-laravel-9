<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apploginhistory extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'ip_address',
  ];

  public function user()
  {
      return $this->belongsTo(User::class, 'user_id', 'id');
  }

}
