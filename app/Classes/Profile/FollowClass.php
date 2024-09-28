<?php
namespace App\Classes\Profile;
use Exception;
use App\Files;
use App\Classes;
use App\Follow;
use Illuminate\Support\Facades\Auth;
use Request;
use Illuminate\Support\Facades\DB;

class FollowClass {

  public static function show()
  {
    $class = Auth::user()->hash;
    $followed = Follow::where('user',$class)->where('type', 'm')->with('file')->get();
    return $followed;
  }
  public static function follow($hash,$type)
  {
    if(Auth::check())
    {
      $class = Auth::user()->hash;
    }
    else
    {
      $class = Request::ip();
    }
    $returnHash = '';
    if($type == 'media')
    {
      #check media hash or class hash exist
      $file = Files::where('hash',$hash)->first();
      echo count($file);
      if(!$file)
      {
        throw new Exception("این رسانه وجود ندارد");
      }
      #if media update or create infos{user(if log user hash | if not log Ip) && file hash && date && type=m} in database and plus one like to this media
      $follow = Follow::firstOrNew(["target"=>$file->hash,"user"=>$class,"type"=>'m']);
      if(!$follow->exists)
      {
        $follow->date = DB::raw('NOW()');
        $follow->save();
        $file->likes +=1;
        $file->save();
        $returnHash = $file->hash;
      }
    }
    elseif($type=='class')
    {
      #check media hash or class hash exist
      $channel = Classes::where('hash',$hash)->first();
      if(!$channel)
      {
        throw new Exception("این کانال وجود ندارد");
      }
      $follow = Follow::firstOrNew(["target"=>$channel->hash,"user"=>$class,"type"=>'c']);
      if(!$follow->exists)
      {
        $follow->date = DB::raw('NOW()');
        $follow->save();
        $returnHash = $channel->hash;
      }
    }
    return $returnHash;
  }
  public static function unFollow($hash,$type)
  {
    if(Auth::check())
    {
      $class = Auth::user()->hash;
    }
    else
    {
      $class = Request::ip();
    }
    $returnHash = '';
    #if class delete infos in database
    if($type == 'media')
    {
      #check media hash or class hash exist
      $file = Files::where('hash',$hash)->first();
      if(!$file)
      {
        throw new Exception("این رسانه وجود ندارد");
      }
      #if media delete infos in database and mines one like to this media
      $follow = Follow::where("target",$hash)->where("user",$class)->where("type",'m');
      if(count($follow)>0)
      {
        $follow->delete();
        $file->likes -=1;
        $file->save();
        $returnHash = $file->hash;
      }
    }
    elseif($type=='class')
    {
      #check media hash or class hash exist
      $channel = Classes::where('hash',$hash)->first();
      if(!$channel)
      {
        throw new Exception("این کانال وجود ندارد");
      }
      #if class update or create infos{user(if log user hash | if not log Ip) && class hash && date && type=c} in database
      $follow = Follow::where("target",$hash)->where("user",$class)->where("type",'c');
      if(count($follow)>0)
      {
        $follow->delete();
        $returnHash = $channel->hash;
      }
    }
    return $returnHash;
  }
}
 ?>
