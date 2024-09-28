<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
  protected $fillable = ['text_title', 'writer','mdate','content','faid','is_news'];

  protected $table = 'page_post';
  public $timestamps = false;

  public function group()
  {
    return $this->belongsTo('App\BlogGroup','faid','id');
  }
}
