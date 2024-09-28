<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
  protected $fillable = [
      'identify','value'
  ];
  protected $table = 'tbl_setting';
  public $timestamps = false;
}
