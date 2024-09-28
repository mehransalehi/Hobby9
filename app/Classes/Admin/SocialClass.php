<?php
namespace App\Classes\Admin;

use App\Files;
use App;
use File;
use Twitter;

class SocialClass {
  public static function sentToFacebook($hash)
  {
    $fb = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
    $data = self::returnMediaStaff($hash);

    $token ='EAAGIenwp4qcBALMpMbroUxOxM9y5TZBF7CyyqxK9mfYgrIlii2kZBszdJkbw0YXkkjjejKMWTqV1Eekk5HEOSMsn1CgjCOt5RaJjESHj3MSVxKXswEi0P126X63BsHaQYEDmeOejUxsFinHIoYGP012NZBo1dGVfXmVs1SwhQZDZD';

    $linkData = [
      'link' => $data['fulurl'],
      'message' => $data['des'],
      'image' => $fb->fileToUpload($data['path']),
      ];

    try {
      // 466400200079875 is Facebook id of Fan page https://www.facebook.com/pontikis.net
      $ret = $fb->post('/854154167945079/feed', $linkData, $token);
      echo 'Successfully posted to Facebook Fan Page';
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      echo $e->getMessage();
      return false;
    }
    return true;
  }
  public static function sentToInsta($hash)
  {
    $data = self::returnMediaStaff($hash);
    set_time_limit(0);
    date_default_timezone_set('UTC');

    /////// CONFIG ///////
    $username = 'hobby9com';
    $password = 'godvsdevil|&';
    //////////////////////
    if($data['type'] == 1)
    {
      /////// MEDIA ////////
      $filename = $data['path'];
      $caption = $data['des'].PHP_EOL.PHP_EOL.
      $data['fulurl'].PHP_EOL.PHP_EOL.
      '#hobby9com';
      //////////////////////

    }
    else
    {
        /////// MEDIA ////////
        $filename = $data['image'];
        $caption = $data['des'].PHP_EOL.PHP_EOL.
        $data['url'].PHP_EOL.PHP_EOL.
        '#hobby9com';
        //////////////////////

    }
    $caption = 'This is a test';
    // Define the user agent
    $agent = self::GenerateUserAgent();
    // Define the GuID
    $guid = self::GenerateGuid();
    // Set the devide ID
    $device_id = "android-".$guid;
    /* LOG IN */
    // You must be logged in to the account that you wish to post a photo too
    // Set all of the parameters in the string, and then sign it with their API key using SHA-256
    $data ='{"device_id":"'.$device_id.'","guid":"'.$guid.'","username":"'.$username.'","password":"'.$password.'","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
    $sig = self::GenerateSignature($data);
    $data = 'signed_body='.$sig.'.'.urlencode($data).'&ig_sig_key_version=4';
    $login = self::SendRequest('accounts/login/', true, $data, $agent, false);

    if(strpos($login[1], "Sorry, an error occurred while processing this request."))
    {
      echo "Request failed, there's a chance that this proxy/ip is blocked";
      return false;
    }
    else
    {
      if(empty($login[1]))
      {
        echo "Empty response received from the server while trying to login";
        return false;
      }
      else
      {
          // Decode the array that is returned
          $obj = @json_decode($login[1], true);
          var_dump($obj);
          if(empty($obj)) {
              echo "Could not decode the response: ".$body;
          } else {
            echo PHP_EOL."باموفقیت لاگین شد.".PHP_EOL;
              // Post the picture
              $data = self::GetPostData($filename);
              $post = self::SendRequest('media/upload/', true, $data, $agent, true);
              if(empty($post[1]))
              {
                echo "Empty response received from the server while trying to post the image";
                return false;
              }
              else
              {
                  // Decode the response
                  $obj = @json_decode($post[1], true);
                  var_dump($obj);
                  if(empty($obj))
                  {
                      echo "Could not decode the response";
                      return false;
                  }
                  else
                  {
                      echo PHP_EOL."با موفقیت پست شد.".PHP_EOL;
                      $status = $obj['status'];
                      if($status == 'ok')
                      {
                          // Remove and line breaks from the caption
                          $caption = preg_replace("/\r|\n/", "", $caption);

                          $media_id = $obj['media_id'];
                          $device_id = "android-".$guid;
                          $data = '{"device_id":"'.$device_id.'","guid":"'.$guid.'","media_id":"'.$media_id.'","caption":"'.trim($caption).'","device_timestamp":"'.time().'","source_type":"5","filter_type":"0","extra":"{}","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
                          $sig = self::GenerateSignature($data);
                          $new_data = 'signed_body='.$sig.'.'.urlencode($data).'&ig_sig_key_version=4';

                         // Now, configure the photo
                         $conf = self::SendRequest('media/configure/', true, $new_data, $agent, true);

                         if(empty($conf[1]))
                         {
                             echo "Empty response received from the server while trying to configure the image";
                             return false;
                         }
                         else
                         {
                             if(strpos($conf[1], "login_required"))
                             {
                                  echo "You are not logged in. There's a chance that the account is banned";
                                  return false;
                             }
                             else
                             {
                                  $obj = @json_decode($conf[1], true);

                                     var_dump($obj);
                                  $status = $obj['status'];

                                  if($status != 'fail') {

                                      echo PHP_EOL."کپشن با موفقیت فرستاده شد".PHP_EOL;
                                      echo "Success";
                                  } else {
                                      echo 'Fail';
                                      return true;
                                  }
                              }
                          }
                      }
                      else
                      {
                          echo "Status isn't okay";
                          return false;
                      }
                  }
              }
          }
      }
    }

    return true;
  }
  public static function sentToGoogle($hash)
  {

  }
  public static function sentToTwitter($hash)
  {
    $data = self::returnMediaStaff($hash);
    if($data['type'] == 1)
    {
      $captionText = $data['des'].PHP_EOL.PHP_EOL.
      $data['fulurl'].PHP_EOL.PHP_EOL.
      '#hobby9com';
    }
    else
    {
        $captionText = $data['des'].PHP_EOL.PHP_EOL.
        $data['url'].PHP_EOL.PHP_EOL.
        '#hobby9com';
    }
    try {
      $uploaded_media = Twitter::uploadMedia(['media' => File::get($data['image'])]);
      Twitter::postTweet(['status' => $captionText, 'media_ids' => $uploaded_media->media_id_string]);
    } catch (\Exception $e) {
      var_dump(Twitter::logs());
        return false;
    }
    return true;
  }

  private static function returnMediaStaff($hash)
  {
    $file = Files::where('hash',$hash)->first();
    $data = array();
    if($file->type == 1)
    {
      $data['des'] = $file->title;
      $data['type'] = 1;
    }
    else
    {
      if($file->class == config('co.radio_user'))
      {
        $data['des'] = 'دانلود آهنگ '.$file->title.' از '.$file->creator.' در لینک زیر';
      }
      else {
        $data['des'] = $file->title.' در لینک زیر';
      }
      $data['type'] = 2;
    }
    $directory = storage_path('app/public/'.config('co.MediaDir'));
    $data['image'] = $directory.'/'.$file->path.'/'.$file->hash.'/'.$file->hash.'_larg.jpg';
    $data['path'] = $directory.'/'.$file->path.'/'.$file->hash.'/'.$file->hash.$file->filetype;
    $data['fulurl'] = url('s/'.$file->hash.'/'.\App\Http\handyHelpers::UE($file->title));
    $data['url'] = url('s/'.$file->hash.'/');
    return $data;
  }


  private static function SendRequest($url, $post, $post_data, $user_agent, $cookies)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://i.instagram.com/api/v1/'.$url);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }

    if($cookies) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
    } else {
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    }

    $response = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

   return array($http, $response);
 }
#INSTAGRAM ADITIONA FUNCTIONS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  private static function GenerateGuid()
  {
       return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand(0, 65535),
              mt_rand(0, 65535),
              mt_rand(0, 65535),
              mt_rand(16384, 20479),
              mt_rand(32768, 49151),
              mt_rand(0, 65535),
              mt_rand(0, 65535),
              mt_rand(0, 65535));
  }

  private static function GenerateUserAgent()
  {
       $resolutions = array('720x1280', '320x480', '480x800', '1024x768', '1280x720', '768x1024', '480x320');
       $versions = array('GT-N7000', 'SM-N9000', 'GT-I9220', 'GT-I9100');
       $dpis = array('120', '160', '320', '240');

       $ver = $versions[array_rand($versions)];
       $dpi = $dpis[array_rand($dpis)];
       $res = $resolutions[array_rand($resolutions)];

       return 'Instagram 4.'.mt_rand(1,2).'.'.mt_rand(0,2).' Android ('.mt_rand(10,11).'/'.mt_rand(1,3).'.'.mt_rand(3,5).'.'.mt_rand(0,5).'; '.$dpi.'; '.$res.'; samsung; '.$ver.'; '.$ver.'; smdkc210; en_US)';
   }

  private static function GenerateSignature($data)
  {
       return hash_hmac('sha256', $data, 'b4a23f5e39b5929e0666ac5de94c89d1618a2916');
  }

  private static function GetPostData($filename)
  {
      if(!$filename) {
          echo "The image doesn't exist ".$filename;
      } else {
          $post_data = array('device_timestamp' => time(),
                          'photo' => '@'.$filename);
          return $post_data;
      }
  }

}

 ?>
