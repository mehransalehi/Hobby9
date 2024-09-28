<?php
namespace App\Classes;
use App\Symlink;
use App\Files;
use Exception;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SymlinkClass{

  public static function create($hash,$path,$ext)
  {

    #check symlink if not exist create
    $directory = storage_path('app/public/'.config('co.MediaDir'));
    $orginalPath = $directory.'/'.$path.'/'.$hash.'/'.$hash.$ext;

    $symDir = public_path().'/'.config('co.dlPath').'/'.$path.'/'.$hash.'/';
    $symPath = $symDir.$hash.$ext;
    if (@readlink($symPath) == false)
		{
      if(@!opendir($symDir))
      {
        $cmd = "mkdir -p $symDir";
        $t = shell_exec($cmd);
        #mkdir($symDir,0644,true);
      }

      if(!symlink($orginalPath , $symPath))
      {
        throw new Exception("سیم لینک ساخته نشد.");
      }
		}


    #for visit we should check symlink table : if this user or this ip
    #do not see this media in past day we should add one to specefic day column in
    #the media table.
    if(Auth::check())
    {
      $class = Auth::user()->hash;
    }
    else {
      $class = Request::ip();
    }
    $syms = Symlink::where('ipaddr',$class)->where('filehash',$hash)->where("hour",">=",DB::raw('DATE_SUB(NOW(),INTERVAL 24 HOUR)'))->get();
    if(count($syms)<1)
    {
      Symlink::create(['ipaddr'=>$class,'hour'=>DB::raw('NOW()'),'filehash'=>$hash]);
      self::addVisit($hash);
    }
    #return symlink url
    $url = url("dl/$path/$hash/$hash$ext");

    return $url;
  }
  public static function addVisit($hash)
  {
		$currentDay = \App\Http\handyHelpers::currentDay();
    $file = Files::where('hash',$hash)->update(['d'.$currentDay => DB::raw('COALESCE(d'.$currentDay.',0)+1'),"visit" => DB::raw('COALESCE(visit,0)+1')]);
    $class = Files::where('hash',$hash)->with('user')->first();
    $class = $class->user;
    if($class)
    {
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
    }
  }
}
 ?>
