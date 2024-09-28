<?php
namespace App\Classes\Admin;

use Telegram\Bot\Api;
use App\Files;

class TeleSend{

  public static function sendMusic($hash,$video = false)
  {
    $file = Files::where('hash',$hash)->where('ispublished',1)->first();

    $directory = storage_path('app/public/'.config('co.MediaDir'));
    $directory .= '/'.$file->path.'/'.$file->hash;

    $picPath = $directory.'/'.$file->hash.'_larg.jpg';

    $tagTitle = str_replace(' ', '_', $file->title);
    $tagCreator = str_replace(' ', '_', $file->creator);
    if($video)
    {
      $caption = 'دانلود موزیک ویدیو '.$file->title.' از '.$file->creator.PHP_EOL.
      '#'.$tagTitle.PHP_EOL.
      '#'.$tagCreator.PHP_EOL;
    }
    else
    {
      $caption = 'دانلود آهنگ '.$file->title.' از '.$file->creator.PHP_EOL.
      '#'.$tagTitle.PHP_EOL.
      '#'.$tagCreator.PHP_EOL;
    }
    $telegram = new Api(config('co.bot_token'));
    $response = $telegram->setAsyncRequest(false)->sendPhoto([
      'chat_id' => config('co.music_channel_id'),
      'photo' => $picPath,
    	'caption' => $caption.'@HOBBY9_RADIO'
    ]);
    var_dump($messageId);
    $messageId = $response->getMessageId();

    $audioPath = $directory.'/'.$file->hash.$file->filetype;
    echo $audioPath;
    $response = $telegram->setAsyncRequest(false)->sendDocument([
      'chat_id' => config('co.music_channel_id'),
      'document' => $audioPath,
    	'caption' => $caption.'پخش آنلاین موسیقی های جدید در: '.PHP_EOL.' http://www.hobby9.com/radio'.PHP_EOL.PHP_EOL.'#HOBBY9.COM'.PHP_EOL.PHP_EOL.'@HOBBY9_RADIO'
    ]);
    $messageId = $response->getMessageId();
  }
}
 ?>
