<?php
namespace App\Classes\Admin;

use App\SpecialBranch;
use App\HomeGroup;

class SpecialBranchClass {
  public static function saveGroup($key,$value)
  {
    if($key == 'none')
    {
      HomeGroup::create(['branch'=>$value['branch'],'hash'=>$value['branch_hash'],'title'=>$value['title'],
      'back_color'=>$value['back_color'],'title_color'=>$value['title_color'],'text_color'=>$value['text_color'],'hr_color'=>$value['hr_color'],
      'text_hover'=>$value['text_hover'],'title_hover'=>$value['title_hover'],'background_image_link'=>$value['link'],'order'=>$value['order']]);
    }
    else
    {
      HomeGroup::where('id',$value['id'])->update(['branch'=>$value['branch'],'hash'=>$value['branch_hash'],'title'=>$value['title'],
      'back_color'=>$value['back_color'],'title_color'=>$value['title_color'],'text_color'=>$value['text_color'],'hr_color'=>$value['hr_color'],
      'text_hover'=>$value['text_hover'],'title_hover'=>$value['title_hover'],'background_image_link'=>$value['link'],'order'=>$value['order']]);
    }
  }
  public static function saveSpecial($key,$value)
  {
    if($key == 'none')
    {
      SpecialBranch::create(['text'=>$value['text'],'icon'=>$value['icon'],'text_color'=>$value['text_color'],
      'icon_color'=>$value['icon_color'],'border_color'=>$value['border_color'],'back_color'=>$value['back_color'],'hr_color'=>$value['hr_color'],'link'=>$value['link']]);
    }
    else
    {
      SpecialBranch::where('id',$value['id'])->update(['text'=>$value['text'],'icon'=>$value['icon'],'text_color'=>$value['text_color'],
      'icon_color'=>$value['icon_color'],'border_color'=>$value['border_color'],'back_color'=>$value['back_color'],'hr_color'=>$value['hr_color'],'link'=>$value['link']]);
    }
  }
  public static function delGroup($id)
  {
    return HomeGroup::where('id',$id)->delete();
  }
  public static function delSpecial($id)
  {
    return SpecialBranch::where('id',$id)->delete();
  }
  public static function returnGroup()
  {
    return HomeGroup::all();
  }
  public static function returnSpecial()
  {
    return SpecialBranch::all();
  }
}

 ?>
