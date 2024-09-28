<?php
namespace App\Classes\Profile;

use App\Files;
use App\Classes;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class FileListClass {
  public static function returnUserFiles($type)
  {
    $class = Auth::user()->hash;

    $files = Files::where('class',$class)->where(function($query){
      $query->where('ispublished',1)->orWhere('ispublished',2)->orWhere('ispublished',5);
    })->where(function($query) use ($type){
      if ($type == 'video')
        $query->where('type',1);
      elseif ($type == 'sound')
        $query->where('type',3);
      elseif ($type == 'ebook')
        $query->where('type',2);
    })->with('user')->with('coQueue')->orderBy('endate','desc')->simplePaginate(config('co.profileFilelistPerPage'));
    return $files;
  }
  public static function returnTopMedia()
  {
    $top = Auth::user()->top_media;
    #$top = Classes::select("top_media")->where('hash',$class)->first()->top_media;
    if(!$top)
    {
      $top = false;
    }
    return $top;
  }
  public static function delMedia($hash)
  {
    $class = Auth::user()->hash;
    $file = Files::where('hash',$hash)->where('class',$class)->first();
    if(count($class)>0)
    {
      $file->ispublished = 4;
      $file->save();
    }
    return true;
  }
  public static function returnMedia($hash)
  {
    $class = Auth::user()->hash;
    $files = Files::where('class',$class)->where('hash',$hash)->where(function($query){
      $query->where('ispublished',1)->orWhere('ispublished',2)->orWhere('ispublished',5);
    })->with('user')->get();

    if(!count($files))
    {
      return false;
    }
    return $files[0];
  }
  public static function saveDetails($input,$pic,$hash)
  {
    $class = Auth::user()->hash;
    if(!in_array($input['category'],config('co.categorys')))
  	{
  		$input['category'] = 'متفرقه';
  	}
    if($input['language'] != 'فارسی')
    {
      $input['language'] = 'غیرفارسی';
    }
    $soflag = 'b';
  	if($input['comment']=='no' && $input['ath'] == 'no')
  	{
  		$soflag = 'n';
  	}
  	elseif($input['comment']=='no')
  	{
  		$soflag = 's';
  	}
  	elseif($input['ath'] == 'no')
  	{
  		$soflag = 'c';
  	}

    $file = Files::where('hash',$hash)->where('class',$class)->with('coQueue')->first();

    if(!$file)
      throw new Exception("اطلاعات موجود نیست.");

    if(array_key_exists('top_media', $input) && @$input['top_media'] == 'set')
  	{
  		Classes::where('hash',$class)->update(["top_media"=>$file->hash]);
  	}

    $file->title = $input['title'];
    $file->creator = $input['author'];
    $file->publisher = $input['publisher'];
    $file->tags = $input['tag'];
    $file->explenation = $input['des'];
    $file->lang = $input['language'];
    $file->branch = $input['category'];
    $file->fabranch = $input['fabranch'];
    $file->soflag = $soflag;
    $file->ispublished = 1;
    if($file->coQueue)
      $file->ispublished = 3;


    #upload Pic if exists
    if($pic)
    {
      if(!$pic->isValid())
      {
        throw new Exception("عکس به درستی آپلود نشده است.");
      }
      $guessExt = '.'.$pic->getClientOriginalExtension();
      $guessMime = $pic->getMimeType();
      if(!in_array($guessMime, config('co.allowedPicMimes')) || !in_array($guessExt, config('co.allowedPicExtensions')))
      {
        throw new Exception("عکس سفارشی نامعتبر است");
      }
      $size = $pic->getClientSize() / 1024 / 1024;
      if($size > config('co.customPicSize'))
      {
        throw new Exception("اندازه عکس سفارشی باید حداکثر ".config('co.customPicSize')." مگا بایت باشد.");
      }
      $hash = md5($pic->getClientOriginalName().date("YmdHis").rand(1,10000));
      $pic->storeAs(config('co.tmpPicDir'), $hash.$guessExt);

      $tmpPath = storage_path('app/'.config('co.tmpPicDir').'/'.$hash.$guessExt);
      $directory = storage_path('app/public/'.config('co.MediaDir').'/'.$file->path.'/'.$file->hash.'/'.$file->hash);

      exec('convert '.$tmpPath.' -resize 180X100\! '.$directory.'_small.jpg');
      exec('convert '.$tmpPath.' -resize 200X200\! '.$directory.'_larg.jpg');

      Storage::delete($tmpPath);
    }

    $file->save();
    return true;
  }
}
?>
