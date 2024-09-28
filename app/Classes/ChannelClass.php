<?php
namespace App\Classes;

use App\Classes;
use App\Files;
use App\UserBranches;
use App\UserSetting;
use Illuminate\Support\Facades\Auth;
use Request;
use Illuminate\Support\Facades\DB;
use App\Follow;

class ChannelClass{
  public static function returnUser($hash)
  {
    $class = Classes::where('hash',$hash)->first();
    $visit = $class->visit;
    if(empty($visit))
    	$visit = '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';


    $numbers = str_split($visit, 6);
    $cDay = \App\Http\handyHelpers::dayOfMonth(true);
    $number = ltrim($numbers[$cDay-1], '0')+1;
    $numbers[$cDay-1] = str_pad($number, 6, "0", STR_PAD_LEFT);
    $number = implode("", $numbers);
    $class->visit=$number;
    $class->save();
    return $class;
  }
  public static function returnUserFiles($hash)
  {
    $result = Files::where('class',$hash)->where(function($query){
      $query->where('ispublished',1)->orWhere('ispublished',5);
    })->orderBy('id', 'desc')->simplePaginate(config('co.itemSearchPage'));
    return $result;
  }
  public static function returnTopMedia($hash)
  {
    $top = Classes::select("top_media")->where('hash',$hash)->first()->top_media;
    if(!$top)
    {
      return false;
    }

    $result = Files::where('class',$hash)->where('hash',$top)->where(function($query){
      $query->where('ispublished',1)->orWhere('ispublished',5);
    })->first();
    return $result;
  }
  public static function returnBranchFiles($hash,$type)
  {
    if($type == 'video' || $type == 'music' || $type == 'ebook')
    {
      $result = Files::where('class',$hash)->where(function($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->where(function($query) use ($type){
        if ($type == 'video')
          $query->where('type',1);
        elseif ($type == 'music')
          $query->where('type',3);
        elseif ($type == 'ebook')
          $query->where('type',2);
      })->orderBy('endate','desc')->simplePaginate(config('co.itemSearchPage'));
    }
    else
    {
      $result = Files::where('class',$hash)->where('fabranch',$type)->where(function($query){
        $query->where('ispublished',1)->orWhere('ispublished',5);
      })->orderBy('endate', 'desc')->simplePaginate(config('co.itemSearchPage'));
    }
    return $result;
  }
  public static function returnUserBranches($hash)
  {
    $branches = UserBranches::where('user',$hash)->with('files')->get();
    return $branches;
  }
  public static function returnUserSetting($hash)
  {
    $setting = UserSetting::where('user_hash',$hash)->first();
    return $setting;
  }
  public static function isFollowed($hash)
  {
    if(Auth::check())
    {
      $class = Auth::user()->hash;
    }
    else
    {
      $class = Request::ip();
    }
    $follow = Follow::where("target",$hash)->where("user",$class)->where("type",'c')->first();
    if(count($follow)>0)
    {
      return true;
    }
    return false;
  }
}
 ?>
