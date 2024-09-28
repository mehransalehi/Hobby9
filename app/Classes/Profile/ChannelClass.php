<?php
namespace App\Classes\Profile;
use App\Classes;
use App\Follow;
use Illuminate\Support\Facades\Auth;

class ChannelClass{
  public static function returnFollowedChannel()
  {
    $class = Auth::user()->hash;
    $result = Follow::where('user',$class)->where('type','c')->orderBy('date','desc')->with('targetChannel')->get();
    return $result;
  }
  public static function returnChannelFollowMe()
  {
    $class = Auth::user()->hash;
    $result = Follow::where('target',$class)->where('type','c')->orderBy('date','desc')->with('userChannel')->get();
    return $result;
  }
}
 ?>
