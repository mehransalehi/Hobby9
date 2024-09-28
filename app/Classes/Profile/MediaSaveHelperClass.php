<?php
namespace App\Classes\Profile;

use imagick;

class MediaSaveHelperClass
{
  public static function returnType($ext)
  {
    if(in_array($ext,config('co.videoExt')))
		{
			return 1;//video id
		}
		elseif(in_array($ext,config('co.bookExt')))
		{
			return 2;//book id
		}
		elseif(in_array($ext,config('co.audioExt')))
		{
			return 3;//audio id
		}
		else
		{
			return 0;//None id
		}
  }
  public static function returnMp3Duration($des)
  {
    $time = exec(config('co.ffmpegPath')." -i $des 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
		list($hms, $milli) = explode('.', $time);
		list($hours, $minutes, $seconds) = explode(':', $hms);
		if(!empty($hours))
			return "$hours:$minutes:$seconds";
		return "$minutes:$seconds";
  }
  public static function returnPdfPage($des)
  {
    exec('/usr/bin/pdfinfo '.$des.' | awk \'/Pages/ {print $2}\'',$output);
		return $output[0];
  }
  public static function makePdfThumb($des,$ext)
  {
    $im = new imagick($des.$ext.'[0]');
		$im->setImageFormat('jpg');
		$im->scaleImage(312,492);
		$im->writeImage($des.'_larg.jpg');

		$im->scaleImage(180,200);
		$im->writeImage($des.'_small.jpg');

		$im->scaleImage(180,100);
		$im->writeImage($des.'_small2.jpg');
  }
  public static function makeVideoThumb($des,$ext)
  {
    $cmd = config('co.ffmpegPath')." -itsoffset -10 -i \"".$des.$ext."\" -vcodec mjpeg -vframes 1 -an -f rawvideo -s 426x240 \"".$des.".jpg\"";
    exec($cmd);
  }
  public static function returnVideoDuration($des)
  {
    $command = config('co.ffmpegPath').' -i "'.$des.'" 2>&1 | grep \'Duration\' | cut -d \' \' -f 4 | sed s/,// &';
		return shell_exec($command);
  }
  public static function mediaSize($des,$precision=3)
  {
    $size='';
		if(!($size=filesize($des)))
		{
			return false;
		}
		$base = $size /1024/1024;

		return round($base,$precision);
  }
  public static function endate()
	{
		return date("Y/F/d");
	}
}
 ?>
