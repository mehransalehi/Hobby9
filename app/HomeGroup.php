<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeGroup extends Model
{
  protected $fillable = ['branch','hash','title', 'back_color', 'title_color', 'text_color', 'title_hover', 'text_hover', 'hr_color', 'background_image_link','order'];

  protected $table = 'home_group';
  public $timestamps = false;
}
