<?php
namespace App\Classes\Profile;


use Illuminate\Support\Facades\Auth;
use App\Classes;
use App\UserSetting;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingClass {
  public static function returnSetting()
  {
    $class = Auth::user()->hash;
    $user = Classes::where('hash',$class)->with('setting')->get();
    return $user[0];
  }
  public static function saveGeneral($input)
  {
    $class = Auth::user()->hash;
    UserSetting::updateOrCreate(['user_hash'=>$class],
    ["fb_addr"=>$input['fb_addr'],"tw_addr"=>$input['tw_addr'],"gp_addr"=>$input['gp_addr']]);

    Classes::where('hash',$class)->update(['name'=>$input['_name'],'des'=>$input['des'],'link'=>$input['link']]);

    if(!empty($input['pass']))
    {
      if($input['pass'] != $input['confpass'])
      {
        throw new Exception("کلمه عبور وارد شده با تکرار آن یکی نیست.");
      }
      Classes::where('hash',$class)->update(['password'=>Hash::make($input['pass'])]);
    }
    return true;
  }
  public static function showCorp($pic)
  {
  	$cWidth = 470;
  	$cHeight = 460;
    $size = $pic->getClientSize() / 1024 / 1024;
    if($size > config('co.userPicUploadLimit'))
    {
      throw new Exception("حجم عکس بیشتر از " . config('co.userPicUploadLimit') . " است.");
    }
    $guessExt = '.'.$pic->getClientOriginalExtension();
    $guessMime = $pic->getMimeType();
    if(!in_array($guessMime, config('co.allowedPicMimes')) || !in_array($guessExt, config('co.allowedPicExtensions')))
    {
      throw new Exception("عکس نا معتبر است.");
    }
    $hash = Auth::user()->hash;
    $pic->storeAs(config('co.tmpUserPicDir'), $hash.$guessExt);

    $tmpPath = storage_path('app/'.config('co.tmpUserPicDir').'/'.$hash.$guessExt);

    list($width, $height) = getimagesize($tmpPath);
    if($width > $cWidth || $height > $cHeight)
    {
      if($width > $height)
      {
        $rate = round($height/$width,2);
        $cHeight = $cWidth * $rate;
      }
      elseif($width < $height)
      {
        $rate = round($width/$height,2);
        $cWidth = $cHeight * $rate;
      }

      exec('convert '.$tmpPath.' -resize '.$cWidth.'x'.$cHeight.'\! '.$tmpPath);
    }
    else
    {
      $cWidth = $width;
      $cHeight = $height;
    }

    #check symlink if not exist create

    $symDir = public_path().'/'.config('co.tmpUserPicPublicDir').'/';
    $symPath = $symDir.$hash.$guessExt;
    if (@readlink($symPath) == false)
		{
      if(@!opendir($symDir))
      {
        $cmd = "mkdir -p $symDir";
        $t = shell_exec($cmd);
        #mkdir($symDir,0644,true);
      }

      if(!symlink($tmpPath , $symPath))
      {
        throw new Exception("سیم لینک ساخته نشد.");
      }
		}
    $url = url(config('co.tmpUserPicPublicDir')."/".$hash.$guessExt);

    $picInfo = array(
      'width' => $cWidth,
      'height'  => $cHeight,
      'ext' => $guessExt,
      'hash'  =>  $hash,
      'url' =>  $url,
    );
    return $picInfo;
  }
  public static function saveCorped($input)
  {
    $path = Auth::user()->pic_path;
    $class = Auth::user()->hash;
    if(empty($path))
    {
      $path = \App\Http\handyHelpers::returnRandomToken();
      Classes::where('hash',$class)->update(['pic_path'=>$path]);
    }

    if(!in_array($input['ext'], config('co.allowedPicExtensions')))
    {
      throw new Exception("عکس نا معتبر است.");
    }
    $ext = $input['ext'];
    $tmpPath = storage_path('app/'.config('co.tmpUserPicDir').'/'.$class.$ext);
    list($width, $height) = getimagesize($tmpPath);

    $x = round($input['x1'], 0, PHP_ROUND_HALF_DOWN);
    $y = round($input['y1'], 0, PHP_ROUND_HALF_DOWN);
    $picWidth = round($input['w'], 0, PHP_ROUND_HALF_DOWN);
    $picHeight = round($input['h'], 0, PHP_ROUND_HALF_DOWN);

    if(($x + $picWidth) > $width)
    {
    	$picWidth = $width - $x;
    }
    if(($y + $picHeight) > $height)
    {
    	$picHeight = $height - $y;
    }
    $directory = storage_path('app/public/'.config('co.tmpUserPicPublicDir'));
    $savePath = $directory.'/'.$path.'/'.$class.'/';
    if(@!opendir($savePath))
    {
      $cmd = "mkdir -p $savePath";
      $t = shell_exec($cmd);
    }
    exec('convert '.$tmpPath.' -crop '.$picWidth.'x'.$picHeight.'+'.$x.'+'.$y.' '.$savePath.$class."_croped.jpg");
  	exec('convert '.$savePath.$class.'_croped.jpg -resize 230x220\! '.$savePath.$class."_larg.jpg");
  	exec('convert '.$savePath.$class.'_croped.jpg -resize 28x28\! '.$savePath.$class."_small.jpg");

    Storage::delete(config('co.tmpUserPicDir').'/'.$class.$ext);
    Storage::delete('public/'.config('co.tmpUserPicPublicDir').'/'.$path.'/'.$class.'/'.$class."_croped.jpg");
    return true;
  }
}
?>
