<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoQueue extends Model
{
  #protected $connection = 'mysql1';
  protected $fillable = ['temp_hash', 'file_hash', 'ext', 'full_path','class','date','status'];

  protected $table = 'tbl_converter_queue';
  public $timestamps = false;

  public function file()
  {
    return $this->belongsTo('App\Files','file_hash','hash');
  }
}
