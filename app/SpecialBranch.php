<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialBranch extends Model
{
  protected $fillable = ['text', 'icon', 'icon_color', 'text_color', 'border_color', 'back_color', 'hr_color', 'link'];

  protected $table = 'special_branch';
  public $timestamps = false;
}
