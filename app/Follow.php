<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
  protected $fillable = ['user', 'target', 'date', 'type'];

  protected $table = 'tbl_follow';
  public $timestamps = false;

  public function file()
  {
      return $this->belongsTo('App\Files','target','hash');
  }
  public function targetChannel()
  {
      return $this->belongsTo('App\Classes','target','hash');
  }
  public function userChannel()
  {
      return $this->belongsTo('App\Classes','user','hash');
  }
}
