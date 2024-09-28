<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
  protected $fillable = ['hash', 'sender', 'date'];

  protected $table = 'comment_report';
  public $timestamps = false;
}
