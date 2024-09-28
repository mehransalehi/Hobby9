<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
  protected $fillable = ['email', 'hash'];

  protected $table = 'request_reg';
  public $timestamps = false;
}
