<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
  protected $fillable = ['feedback', 'sender', 'ip', 'date', 'email', 'is_read'];
  protected $table = 'feedback_report';
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo('App\Classes','sender','hash');
  }
}
