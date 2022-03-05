<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nmitransactions extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'updated_at',
  ];


  public function user()
  {
      return $this->belongsTo(User::class, 'email', 'email');
  }

}
