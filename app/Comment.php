<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $fillable = ['user_hash', 'comment', 'hash', 'file', 'm_date', 'likes', 'dislike','is_read','replay'];

  protected $table = 'comments';
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo('App\Classes','user_hash','hash');
  }
  public function media()
  {
    return $this->belongsTo('App\Files','file','hash');
  }
}
