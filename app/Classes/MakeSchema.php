<?php
namespace App\Classes;

use Spatie\SchemaOrg\Schema;

class MakeSchema {
  public static function retunShowMedia($media,$path)
  {
    if($media->type == 1){
      return self::videoSchema($media,$path);
    }
    elseif($media->type == 2){
      return self::bookSchema($media,$path);
    }elseif($media->type == 3){
      return self::musicSchema($media,$path);
    }

  }
  public static function videoSchema($media,$path)
  {
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');
    $time = self::uploadDate($media->endate);
    $dur = self::timeString($media->pagetime);
    $embed = url('/video/embed/hash/'.$media->hash.'/mt/frame');
    $exp = $media->explenation;
    if(empty($media->explenation))
    {
      $exp = 'توضیحاتی برای رسانه'.$media->title.' درج نشده است.';
    }
    $obj = Schema::VideoObject()
    ->name($media->title)
    ->Description($exp)
    ->thumbnailUrl($thumb)
    ->uploadDate($time)
    ->duration($dur)
    ->contentUrl($path)
    ->embedUrl($embed)
    ->interactionCount($media->visit);

    $obj->toScript();
    return $obj;
  }
  public static function bookSchema($media,$path)
  {
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');
    $exp = $media->explenation;
    if(empty($media->explenation))
    {
      $exp = 'توضیحاتی برای رسانه'.$media->title.' درج نشده است.';
    }
    $creator = $media->creator;
    if(empty($media->creator))
    {
      $creator = 'نامشخص';
    }
    $url = url('s/'.$media->hash).'/'.\App\Http\handyHelpers::UE($media->title);

    $obj = Schema::Book()
    ->author(Schema::Person()->name($creator))
    ->name($media->title)
    ->url($url)
    ->isFamilyFriendly('http://schema.org/True')
    ->Description($exp)
    ->thumbnailUrl($thumb)
    ->numberOfPages($media->pagetime);

    $obj->toScript();
    return $obj;
  }
  public static function musicSchema($media,$path)
  {
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');
    $time = self::uploadDate($media->endate);
    $dur = self::timeString($media->pagetime);
    $embed = url('/video/embed/hash/'.$media->hash.'/mt/frame');
    $exp = $media->explenation;
    if(empty($media->explenation))
    {
      $exp = 'توضیحاتی برای رسانه'.$media->title.' درج نشده است.';
    }
    $url = url('s/'.$media->hash).'/'.\App\Http\handyHelpers::UE($media->title);

    $obj = Schema::AudioObject()
    ->name($media->title)
    ->url($url)
    ->isFamilyFriendly('http://schema.org/True')
    ->Description($exp)
    ->thumbnailUrl($thumb)
    ->uploadDate($time)
    ->duration($dur)
    ->contentUrl($path)
    ->embedUrl($embed)
    ->interactionCount($media->visit);

    $obj->toScript();

    $obj1 = Schema::MusicAlbum()
    ->name($media->title)
    ->url($url)
    ->Description($exp);

    $obj->toScript();
    return $obj.$obj1;
  }
  public static function returnMetaTags($media,$path)
  {
    if($media->type == 1){
      return self::videoMeta($media,$path);
    }
    elseif($media->type == 2){
      return self::bookMeta($media,$path);
    }elseif($media->type == 3){
      return self::musicMeta($media,$path);
    }
  }
  public static function videoMeta($media,$path)
  {
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');

    $meta = '
      <meta property="og:type" content="video">
      <meta property="og:video:type" content="application/x-shockwave-flash">
      <meta property="og:video:url" content="'.$path.'/">
      <meta property="og:video:secure_url" content="'.$path.'">
      <meta property="og:video:width" content="750">
      <meta property="og:video:height" content="450">
      <meta name="video_type" content="video/mp4">
      <meta property="og:image" content="'.$thumb.'" />
      <meta property="og:image:secure_url" content="'.$thumb.'">
      <meta property="og:image:width" content="426">
      <meta property="og:image:height" content="240">
      <meta property="og:image:type" content="image/jpg">';

    return self::simMeta($media,$path,$meta,'video');
  }
  public static function bookMeta($media,$path)
  {
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');
    $meta = '
      <meta property="og:type" content="book">
      <meta property="og:book:type" content="pdf">
      <meta property="og:book:url" content="'.$path.'/">
      <meta property="og:image" content="'.$thumb.'" />
      <meta property="og:image:secure_url" content="'.$thumb.'">
      <meta property="og:image:width" content="312">
      <meta property="og:image:height" content="492">
      <meta property="og:image:type" content="image/jpg">';

    return self::simMeta($media,$path,$meta,'book');
  }
  public static function musicMeta($media,$path)
  {
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');
    $meta = '
      <meta property="og:type" content="audio">
      <meta property="og:audio:type" content="application/x-shockwave-flash">
      <meta property="og:audio:url" content="'.$path.'/">
      <meta property="og:video:secure_url" content="'.$path.'">
      <meta name="audio_type" content="audio/mp3">
      <meta property="og:image" content="'.$thumb.'" />
      <meta property="og:image:secure_url" content="'.$thumb.'">
      <meta property="og:image:width" content="200">
      <meta property="og:image:height" content="200">
      <meta property="og:image:type" content="image/jpg">';

    return self::simMeta($media,$path,$meta,'audio');
  }
  public static function simMeta($media,$path,$meta,$type)
  {
    $metaDes = '(';
    $mytags = explode("-",$media->tags);

    $keywords = '';
    for ($j=0;$j<count($mytags);$j++)
    {
      if(strlen($mytags[$j]) < 2)
      {
        continue;
      }
      $metaDes .= ' '.$mytags[$j];
      if(($j+1) == count($mytags))
      {
          $keywords .= $mytags[$j];
      }
      else
      {
          $keywords .= $mytags[$j].',';
      }
    }
    $metaDes .= ')';
    $thumb = url('includes/returnpic.php?type='.$media->type.'&picid='.$media->hash.'&p='.$media->path.'&s=1');
    $time = self::uploadDate($media->endate);
    $dur = self::timeString($media->pagetime);
    $embed = url('/video/embed/hash/'.$media->hash.'/mt/frame');
    $exp = $media->explenation;
    if(empty($media->explenation))
    {
      $exp = 'توضیحاتی برای رسانه'.$media->title.' درج نشده است.';
    }
    $url = url('s/'.$media->hash).'/'.\App\Http\handyHelpers::UE($media->title);

    $meta = '
    <meta name="description" content="'.$exp.$metaDes.'" />
    <meta name="keywords" content="'.$keywords.'" />
    <meta name="DC.Identifier" content="'.$url.'"/>
    <meta name="DC.Date.Created" content="'.$time.'"/>
    <meta name="DC.Type" content="'.$type.'"/>
    <meta name="DC.Title" content="'.$media->title.'"/>
    <meta name="DC.Description" content="'.$exp.'"/>
    <meta name="DC.Language" content="fa"/>

    <link rel="canonical" href="'.$url.'" />
    <meta property="og:url" content="'.$url.'" />
    <meta property="og:site_name" content="هابی ۹">
    <meta property="og:title" content="'.$media->title.'" />
    <meta property="og:description" content="'.$exp.'" />
    '.$meta.'
    <meta name="title" content="'.$media->title.'">';

    return $meta;
  }
  public static function uploadDate($endate)
  {
    $time = strtotime($endate);
		$jdate = new jDateTime(true, true, 'Asia/Tehran');
		@$sMonth = $jdate->date("F", $time, true, true, 'Asia/Tehran');
		@$sDay = $jdate->date("d", $time, true, true, 'Asia/Tehran');
		@$sYear = $jdate->date("Y", $time, true, true, 'Asia/Tehran');

		$structUploadTime = date('o-m-d', $time).'T'.date('H:i:s', $time).'+03:30';
    return $structUploadTime;
  }
  public static function timeString($pgTime)
	{
		$showtime = $structTime = '';
		$times = explode(":",$pgTime);
		if(count($times) == 2 )
		{
			@$hour = 0;
			@$min = (int)$times[0];
			@$sec = (int)$times[1];
		}
		else
		{
			@$hour = (int)$times[0];
			@$min = (int)$times[1];
			@$sec = (int)$times[2];
		}

		if($hour != 0 )
		{
			$hour = str_pad($hour,2,'0', STR_PAD_LEFT);
			$min = str_pad($min,2,'0', STR_PAD_LEFT);
			$sec = str_pad($sec,2,'0', STR_PAD_LEFT);
			$structTime = $hour."H".$min."M".$sec."S";
		}
		elseif($min != 0)
		{
			$min = str_pad($min,2,'0', STR_PAD_LEFT);
			$sec = str_pad($sec,2,'0', STR_PAD_LEFT);
			$structTime = $min."M".$sec."S";
		}
		else
		{
			$sec = str_pad($sec,2,'0', STR_PAD_LEFT);
     	$structTime = $sec."S";
		}
		return $structTime;
	}
}
 ?>
