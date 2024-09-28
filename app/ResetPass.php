<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResetPass extends Model
{
  protected $fillable = ['email', 'hash', 'password'];

  protected $table = 'tbl_recovery';
  public $timestamps = false;
}
