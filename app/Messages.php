<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
  protected $fillable = ['sender', 'reciver', 'date', 'text', 'is_read'];

  protected $table = 'tbl_msg';
  public $timestamps = false;

  public function senderUser()
  {
    return $this->belongsTo('App\Classes','sender','hash');
  }
  public function reciverUser()
  {
    return $this->belongsTo('App\Classes','reciver','hash');
  }
}
