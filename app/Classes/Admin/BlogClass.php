<?php
namespace App\Classes\Admin;

use App\BlogGroup;
use App\BlogPost;
use Illuminate\Support\Facades\DB;

class BlogClass {

  public static function saveGroup($title,$menu_title,$order,$key='none')
  {
    if($key == 'none')
    {
      BlogGroup::create(['page_title'=>$title,'menu_title'=>$menu_title,'menu_order'=>$order]);
    }
    else
    {
      BlogGroup::where('id',$key)->update(['page_title'=>$title,'menu_title'=>$menu_title,'menu_order'=>$order]);
    }
  }
  public static function savePost($title,$writer,$content,$faid,$news,$key='none')
  {
    if($key == 'none')
    {
      BlogPost::create(['text_title'=>$title,'writer'=>$writer,'content'=>$content,'faid'=>$faid,'is_news'=>$news,'mdate'=>DB::raw('NOW()')]);
    }
    else
    {
      BlogPost::where('id',$key)->update(['text_title'=>$title,'writer'=>$writer,'content'=>$content,'faid'=>$faid,'is_news'=>$news,'mdate'=>DB::raw('NOW()')]);
    }
  }
  public static function returnPosts()
  {
    return BlogPost::with('group')->get();
  }
  public static function returnGroups()
  {
    return BlogGroup::all();
  }
  public static function delPost($id)
  {
    return BlogPost::where('id',$id)->delete();
  }
  public static function delBranch($id)
  {
    return BlogGroup::where('id',$id)->delete();
  }
}

 ?>
