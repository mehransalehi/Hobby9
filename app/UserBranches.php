<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBranches extends Model
{
  protected $fillable = ['name', 'hash', 'user'];

  protected $table = 'tbl_user_branch';
  public $timestamps = false;

  public function files()
  {
    return $this->hasMany('App\Files','fabranch','hash');
  }
}
