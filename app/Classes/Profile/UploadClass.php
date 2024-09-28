<?php
namespace App\Classes\Profile;

  ########### WORK PLAN #######################
  # 1- Check size
  # 2- Check mime and extentions
  # 3- Make random hash
  # 4- Save uploaded file as stage 3 hash for name
  # 5- Send Infos to Recorder Classes
  # 6- If recorder Class return true save an Identify for detail submistion
  # 7- Remove tmp file

use Illuminate\Http\UploadedFile;
use App\Classes\Profile\RecordClass;
use Illuminate\Support\Facades\Auth;
use App\UploadIden;
use Illuminate\Support\Facades\Storage;
use App\Files;
use App\Classes;
use Exception;

class UploadClass {

  private $file ;
  private $name ;
  private $ext ;
  function __construct($file)
  {
    $this->file = $file;
    $this->name = $file->getClientOriginalName();
    $this->ext = '.'.$file->getClientOriginalExtension();
  }
  public function upload()
  {
    #{1}
    $size = $this->file->getClientSize() / 1024 / 1024;
    if($size > config('co.fileUploadLimit'))
    {
      throw new Exception("حجم رسانه دریافتی بیشتر از " . config('co.fileUploadLimit') . " است.");
    }
    #{2}
    if(!$this->checkMimes())
    {
      throw new Exception('پسوند یا نوع این رسانه معتبر نیست. رسانه هایی با مشخصات جدول پایین معتبر می باشند.');
    }
    #{3}
    $hash = md5($this->name.date("YmdHis").rand(1,10000));
    #{4}
    $this->file->storeAs(config('co.tmpFileDir'), $hash.$this->ext);
    #{5}
    $record = new RecordClass($hash,$this->ext,$this->name);
    if(($fileHash = $record->save()))
    {
      #{6}
      $this->saveIden($fileHash,$hash);
    }
    else
    {
      throw new Exception('مشکلی در ذخیره اطلاعات رخ داده است با مدیریت تماس بگیرید.');
    }
    return $hash;
  }
  private function saveIden($fileHash,$hash)
  {
    $class = Auth::user()->hash;
    UploadIden::create(["class"=>$class,"hash"=>$hash,"file_hash"=>$fileHash]);
    return true;
  }
  private function checkMimes()
  {
    $guessExt = $this->ext;
    $guessMime = $this->file->getMimeType();
    if(!in_array($guessMime, config('co.allowedMimes')) || !in_array($guessExt, config('co.allowedExtensions')))
    {
      return false;
    }
    return true;
  }
  public static function saveDetails($input,$pic)
  {
    $class = Auth::user()->hash;
    $iden = UploadIden::where('class',$class)->where('hash',$input['hash'])->first();
    if(count($iden)<0)
      throw new Exception("اطلاعات موجود نیست.");

    if(!in_array($input['cat'],config('co.categorys')))
  	{
  		$input['cat'] = 'متفرقه';
  	}
    if($input['lang'] != 'فارسی')
    {
      $input['lang'] = 'غیرفارسی';
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

    $file = Files::where('hash',$iden->file_hash)->where('class',$class)->first();

    if(!$file)
      throw new Exception("اطلاعات موجود نیست.");

    if(in_array('top', $input) && @$input['top'] == 'set')
  	{
  		Classes::where('hash',$class)->update(["top_media"=>$file->hash]);
  	}

    $file->title = $input['title'];
    $file->creator = $input['author'];
    $file->publisher = $input['publisher'];
    $file->tags = $input['tag'];
    $file->explenation = $input['des'];
    $file->lang = $input['lang'];
    $file->branch = $input['cat'];
    $file->fabranch = $input['fabranch'];
    $file->soflag = $soflag;
    $file->ispublished = 1;

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
        throw new Exception("اندازه عکس سفارشی باید حداکثر ".config('co.customPicSize')." باشد.");
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

    $iden->delete();
    return true;
  }
}
 ?>
