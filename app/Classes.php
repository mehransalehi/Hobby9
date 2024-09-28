<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Classes extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'owner', 'name', 'des', 'link', 'password', 'email', 'hash', 'pic_path', 'top_media', 'identifier', 'type', 'bank_name', 'bank_id', 'bank_card', 'income', 'visit', 'telegram_token', 'remember_token','reg_date'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];
  protected $table = 'tbl_classes';
  public $timestamps = false;

  public function files(){
    return $this->hasMany('App\Files','class','hash');
  }
  public function comments(){
    return $this->hasMany('App\Comment','user_hash','hash');
  }
  public function setting()
  {
      return $this->hasOne('App\UserSetting','user_hash','hash');
  }
  public function follow()# من چع کسی را دنبال کرده ام
  {
      return $this->hasMany('App\Follow','user','hash');
  }
  public function target()# من تارگت کی هستم
  {
      return $this->hasMany('App\Follow','target','hash');
  }
}
