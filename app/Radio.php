<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radio extends Model
{
  protected $fillable = ['media_hash', 'date'];

  protected $table = 'tbl_radio_songs';
  public $timestamps = false;

  public function media()
  {
    return $this->belongsTo('App\Files','media_hash','hash');
  }
}
