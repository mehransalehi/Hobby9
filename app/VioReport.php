<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VioReport extends Model
{
  protected $fillable = ['hash', 'min_one', 'min_two', 'min_three', 'report', 'm_date', 'seen', 'category'];

  protected $table = 'violation_rep';
  public $timestamps = false;
}
