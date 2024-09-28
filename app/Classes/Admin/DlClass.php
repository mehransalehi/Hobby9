<?php
namespace App\Classes\Admin;
use App\DLMaster;


class DlClass{
  private static $data ;
  public static function dlSave($data,$isVideo = false,$isMine="NO")
  {
    if(!array_key_exists('user', $data) || !array_key_exists('url', $data))
    {
      return false;
    }
    $dlmaster = DlMaster::where('link',$data['url'])->get();
    if(count($dlmaster)>0)
    {
      return true;
    }
    else
    {
      $dlmaster = new DlMaster;
      $dlmaster->class = $data['user'];
      $dlmaster->link = $data['url'];
      $dlmaster->save();
    }
    $img = @$data['img'];
    $ext = @$data['ext'];

    if(empty($ext))
    {
      $temp = explode(".", $data['url']);
      $ext = strtolower(reset(explode("?", end($temp))));
      $ext = '.'.$ext;
      $data['ext'] = $ext;
    }

    $hash = $picName = $name = md5(date("YmdHis").rand(1,10000));
    $name = $name . $ext;

    if(!empty($img))
    {
      $temp = explode(".", $img);
      $extPic = strtolower(end($temp));
      $extPic = '.'.$extPic;
      $picCName = $picName.$extPic;

      #echo $picCName."===".$picName;exit;
      $directory = storage_path('app/'.config('co.tmpFileDir'));
      $cmdPic = 'aria2c -d '.$directory.'/ -o '.$picCName.' "'.$img.'"';
      echo shell_exec($cmdPic);
      if($isVideo)
      {
        exec('convert '.$directory.'/'.$picCName.' -resize 250x140\! '.$directory.'/'.$picName.'_small.jpg');
        exec('convert '.$directory.'/'.$picCName.' -resize 900x500\! '.$directory.'/'.$picName.'_larg.jpg');
      }
      else
      {
        exec('convert '.$directory.'/'.$picCName.' -resize 180X100\! '.$directory.'/'.$picName.'_small.jpg');
        exec('convert '.$directory.'/'.$picCName.' -resize 200X200\! '.$directory.'/'.$picName.'_larg.jpg');
      }
    }
    $data['name'] = $name;
    $data['mine'] = $isMine;
    $data['userdl'] = 'NO';
    self::$data = $data;
    #$mainCmd = "nohup sh -c 'php ".base_path()."/artisan hobby:aria \"".$data['url']."\" \"$name\" \"$user\" \"$title\" \"$branch\" \"$publisher\" \"$author\" \"$img\" \"$isMine\" \"NO\"' > /dev/null 2> /dev/null & echo $!";
    self::addToDlQueue();
    return $hash;
  }
  public static function userDlSave($data)
  {
    $isMine = 'NO';
    if(!array_key_exists('user', $data) || !array_key_exists('url', $data))
    {
      return false;
    }

    $temp = explode(".", $data['url']);
    $ext = strtolower(end($temp));
    $ext = '.'.$ext;

    $name = $hash = md5(date("YmdHis").rand(1,10000));
    $name = $name . $ext;

    $data['name'] = $name;
    $data['ext'] = $ext;
    $data['mine'] = 'NO';
    $data['userdl'] = 'YES';
    self::$data = $data;
    self::addToDlQueue();

    return $hash;
  }
  private static function addToDlQueue()
  {
    $arguments = self::$data;
    $name = $arguments['name'];
    $user = $arguments['user'];
    $title = empty($arguments['title']) ? "" : $arguments['title'] ;
    $branch = empty($arguments['branch']) ? "" : $arguments['branch'] ;
    $publisher = empty($arguments['publisher']) ? "" : $arguments['publisher'] ;
    $author = empty($arguments['author']) ? "" : $arguments['author'] ;
    $img = empty($arguments['img']) ? "" : $arguments['img'] ;
    $isMine = empty($arguments['mine']) ? "" : $arguments['mine'] ;
    $userDl = empty($arguments['userdl']) ? "" : $arguments['userdl'] ;

    $mainCmd = "nohup sh -c 'php ".base_path()."/artisan hobby:aria \"".$arguments['url']."\" \"$name\" \"$user\" \"$title\" \"$branch\" \"$publisher\" \"$author\" \"$img\" \"$isMine\" \"$userDl\"' > /dev/null 2> /dev/null & echo $!";
    echo PHP_EOL.PHP_EOL.PHP_EOL.$mainCmd.PHP_EOL.PHP_EOL.PHP_EOL;
    popen($mainCmd, 'r');

  }
}
?>
