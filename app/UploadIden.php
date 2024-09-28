<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadIden extends Model
{
  protected $fillable = ['class', 'hash', 'file_hash'];

  protected $table = 'upload_verify';
  public $timestamps = false;
}
