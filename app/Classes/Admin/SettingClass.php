<?php
namespace App\Classes\Admin;

use App\AdminSetting;

class SettingClass {
  public static function save($key,$value)
  {
    AdminSetting::updateOrCreate(['identify'=>$key],['value'=>$value]);
  }
  public static function returnSetting($page)
  {
    return AdminSetting::where('identify','like',''.$page.'%')->get();
  }
}

 ?>
