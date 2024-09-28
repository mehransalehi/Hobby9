<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'identify', 'ad',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $table = 'ads';
  public $timestamps = false;
}
