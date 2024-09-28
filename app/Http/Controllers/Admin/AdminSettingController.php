<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Admin\SettingClass;
use Validator;

class AdminSettingController extends Controller
{
  public function setting($page)
  {
    $settings = SettingClass::returnSetting($page);
    $data = array();
    foreach ($settings as $setting)
    {
      if($setting->identify == 'home_top_media')
      {
        $data['home_top_media-info'] = explode('|', $setting->value)[1];
        $data[$setting->identify] = $setting->value;
      }
      else
      {
        $data[$setting->identify] = $setting->value;
      }

    }
    if($page=='home')
    {

        return view('admin.setting.homeSetting',['data'=>$data]);
    }
  }
  public function saveSetting(Request $request,$page,$type)
  {
    if($page=='home')
    {
      if($type == 'showhide')
      {
        #special page
        if($request->special_page == 'show')
        {
          SettingClass::save('home_special_page','show');
        }
        else
        {
          SettingClass::save('home_special_page','hide');
        }
        #top part
        if($request->top_part == 'show')
        {
          SettingClass::save('home_top_part','show');
        }
        else
        {
          SettingClass::save('home_top_part','hide');
        }
        #top media
        if($request->top_media != 'none')
        {
          $val = Validator::make($request->all(),[
            "hash" => 'required'
          ]);
          if($val->fails() && ($request->top_media == 'hashed' || $request->top_media == 'taged' || $request->top_media == 'searched' || $request->top_media == 'channel'))
          {
            return redirect('/webmaster/setting/home')->withErrors($val);
          }
          SettingClass::save('home_top_media',$request->top_media.'|'.$request->hash);
        }
      }

      $settings = SettingClass::returnSetting($page);
      $data = array();
      foreach ($settings as $setting)
      {
          $data[$setting->identify] = $setting->value;
      }
      $data['status'] = 'success';
      return view('admin.setting.homeSetting',['data'=>$data]);
    }
  }
}
