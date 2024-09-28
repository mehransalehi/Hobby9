<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symlink extends Model
{
  protected $fillable = ['hour', 'filehash', 'ipaddr'];

  protected $table = 'symlinks';
  public $timestamps = false;
}
