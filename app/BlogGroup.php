<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogGroup extends Model
{
  protected $fillable = ['page_title', 'menu_title','menu_order'];

  protected $table = 'page_group';
  public $timestamps = false;

  public function posts()
  {
    return $this->hasMany('App\BlogPost','faid','id');
  }
}
