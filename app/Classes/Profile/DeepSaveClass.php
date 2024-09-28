<?php
namespace App\Classes\Profile;

use App\Classes\Profile\MediaSaveHelperClass;
use App\Files;
use App\Classes\Admin\TeleSend;
use App\CoQueue;
use App\Classes\Profile\ConvertClass;

class DeepSaveClass{
  public static function deepSave($hash,$ext,$path,$user,$tmp)
  {
    $file = Files::where('hash',$hash)->first();

    $file->type = MediaSaveHelperClass::returnType($ext);
    $file->filetype = $ext;
    if($file->type == 3)
		{
			$file->pagetime = MediaSaveHelperClass::returnMp3Duration($path.$ext);
		}
		elseif($file->type == 2)
		{
			$file->pagetime = MediaSaveHelperClass::returnPdfPage($path.$ext);
			MediaSaveHelperClass::makePdfThumb($path,$ext);
		}
		elseif($file->type == 1)
		{
			$file->pagetime = MediaSaveHelperClass::returnVideoDuration($path.$ext);
		}

		$file->volume = MediaSaveHelperClass::mediaSize($path.$ext);
    CoQueue::where('file_hash',$hash)->where('status','co')->delete();
    $poper = new ConvertClass(false,false,false,false);
    $poper->popQueue();

    if($file->class == config('co.radio_user'))
    {
      $directory = storage_path('app/public/'.config('co.MediaDir'));
      $directory .= '/'.$file->path.'/'.$file->hash.'/';
      $cmd = "mv ".storage_path('app/'.config('co.tmpFileDir')).'/'.$tmp.'_small.jpg'." ".$directory.'/'.$file->hash.'_small.jpg';
      shell_exec($cmd);

      $cmd = "mv ".storage_path('app/'.config('co.tmpFileDir')).'/'.$tmp.'_larg.jpg'." ".$directory.'/'.$file->hash.'_larg.jpg';
      shell_exec($cmd);

      $file->ispublished = 1;
    	$file->save();
      TeleSend::sendMusic($file->hash,true);
    }
    else
    {
      if($file->ispublished == 3)
        $file->ispublished = 1;
      $file->save();
    }
  }
}
 ?>
