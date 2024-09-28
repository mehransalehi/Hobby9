<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DLMaster extends Model
{
  protected $fillable = ['class', 'name', 'link', 'rep'];

  protected $table = 'tbl_master_dl';
  public $timestamps = false;
}
