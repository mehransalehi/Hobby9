<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
  protected $fillable = ['fb_addr', 'gp_addr', 'tw_addr', 'border_header_color',
  'main_text_color', 'main_text_color_shadow', 'des_color', 'border_sidebar_color',
  'background_sidebar_head', 'background_sidebar_body', 'text_head_color', 'text_list_color',
  'body', 'body_text', 'key_style', 'user_hash'];
  protected $table = 'tbl_user_setting';
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo('App\Classes','user_hash','hash');
  }
}
