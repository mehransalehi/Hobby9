<?php
namespace App\Classes\Profile;

use Illuminate\Support\Facades\Storage;
use App\Classes\Profile\ConvertClass;
use Illuminate\Support\Facades\Auth;
use App\Classes\Profile\MediaSaveHelperClass;
use App\Files;
use Illuminate\Support\Facades\DB;
use App\Radio;
use App\Classes\Admin\TeleSend;
########### WORK PLAN ############
#1- Make random md5 hash for media
#2- Make directory for media in medias directory | /destination/token/hash/
#3- Check if media need convert ----->
#>3-1 if need send infos to converter class and if return true save infos to database and if false remove temp media
#>3-2 if media do not need convert just copy media to directory path in stage 2
#4- save media info

class RecordClass{

  private $hash;
  private $ext;
  private $token;
  private $name;
  private $fileHash;
  private $filePath;
  private $fileJustPath;
  private $attachInfo;//the array for extra info that you wnat to save for a media;
  function __construct($hash,$ext,$name,$attachInfo=array())
  {
    $this->fileHash = $hash;
    $this->ext = $ext;
    $this->token = \App\Http\handyHelpers::returnRandomToken();
    $this->name = $name;
    $this->attachInfo = $attachInfo;
  }
  public function save()
  {
    $this->hash = md5($this->name.$this->token.date("YmdHis").rand(1,10000));
    $directory = storage_path('app/public/'.config('co.MediaDir'));
    $this->filePath = $directory.'/'.$this->token.'/'.$this->hash.'/'.$this->hash.$this->ext;
    $this->fileJustPath = $directory.'/'.$this->token.'/'.$this->hash.'/'.$this->hash;
    echo "directory : ".$directory;
    echo "path : ".$this->filePath;
    Storage::disk('media')->makeDirectory($this->token.'/'.$this->hash);
    echo "1";
    $extConvert='';

    ######################## STAFF FOR TELEGRAM CONVERT#############################
    $needConvert = 'true';
    if(array_key_exists('user', $this->attachInfo))
    {
      if($this->attachInfo['user'] == 'TELEGRAM')
      {
        $telePath = storage_path('app/'.config('co.tmpFileDir')).'/'.$this->fileHash.$this->ext;
        $teleType = MediaSaveHelperClass::returnType($this->ext);
        if($teleType == 3)
        {
          $telePagetime = MediaSaveHelperClass::returnMp3Duration($telePath);
          if(!\App\Http\handyHelpers::isTele($telePagetime))
          {
            $needConvert = false;
          }
        }
        elseif($teleType == 1)
        {
          $telePagetime = MediaSaveHelperClass::returnVideoDuration($telePath);
          if(!\App\Http\handyHelpers::isTele($telePagetime))
          {
            $needConvert = false;
          }
        }
      }
    }
    #################################################################################


    if(in_array($this->ext, config('co.needConvert')) && $needConvert)
    {
      $converter = new ConvertClass($this->fileJustPath,$this->ext,$this->fileHash,$this->hash);
      $extConvert = $converter->convert();
      $this->saveInfo(true,$extConvert);
      echo "2";
    }
    else
    {
      #copy(storage_path('app/'.config('co.tmpFileDir')).'/'.$this->fileHash.$this->ext, $this->filePath);
      $cmd = "cp ".storage_path('app/'.config('co.tmpFileDir')).'/'.$this->fileHash.$this->ext." ".$this->filePath;
      echo shell_exec($cmd);
      Storage::delete(config('co.tmpFileDir').'/'.$this->fileHash.$this->ext);
      $this->saveInfo(false);
      echo "3";
    }
    return $this->hash;
  }
  private function saveInfo($haveConvert=true,$ext='')
  {
    #$hash = $this->hash;
		#$in_fol = $this->inner_folder;
    echo "4";
    $media = new Files;
    $media->type = MediaSaveHelperClass::returnType($this->ext);

    $media->endate = DB::raw('NOW()');
    $media->hash = $this->hash;
    $media->numdownload = 0;
    $media->price = 0;
    $media->likes = 0;
    $media->filetype = $ext;
    $media->visit = 1;
    $media->servernum = 1;
    if(array_key_exists('user', $this->attachInfo))
    {
      $media->class = $this->attachInfo['user'];
      if($media->class == 'TELEGRAM' && !$haveConvert)
      {
        $media->ispublished = 21;
      }
      elseif($media->class == 'TELEGRAM')
      {
        $media->ispublished = 7;
      }
      elseif($media->class == config('co.radio_user') && !$haveConvert)
      {
        $media->ispublished = 1;
        $media->pagetime = MediaSaveHelperClass::returnMp3Duration($this->filePath);
        echo PHP_EOL.PHP_EOL.PHP_EOL."THIS ISSSSSSSSSSSSSSSSSSSS TIMEEEEEEEEE 1: ".MediaSaveHelperClass::returnMp3Duration($this->filePath).PHP_EOL.PHP_EOL.PHP_EOL;
        $radio = new Radio;
        $radio->media_hash = $media->hash;
        $radio->date = DB::raw('NOW()');
        $radio->save();

        #################### CHANGE ID3 TAGS ##############################
        $TextEncoding = 'UTF-8';
        $getID3 = new \getID3;
        $tagwriter = new \getid3_writetags;
        $file_path = $this->filePath;
        $ThisFileInfo = $getID3->analyze($file_path);
        if(array_key_exists('title', $ThisFileInfo['id3v1']))
        {
          $id_title = $ThisFileInfo['id3v1']['title'];
          $id_artist = $ThisFileInfo['id3v1']['artist'];
          $id_comment = $ThisFileInfo['id3v1']['comment'];
          $id_album = $ThisFileInfo['id3v1']['album'];
        }
        elseif (array_key_exists('title', $ThisFileInfo['id3v2']))
        {
          $id_title = $ThisFileInfo['id3v2']['title'];
          $id_artist = $ThisFileInfo['id3v2']['artist'];
          $id_comment = $ThisFileInfo['id3v2']['comment'];
          $id_album = $ThisFileInfo['id3v2']['album'];
        }
        else {
          echo "NO KEY";
          #exit;
        }
        $id_artist = str_ireplace(array('Nex1Music.IR','Nex1Music','Nex1Musi','Nex1'), "Hobby9.com/Radio", $id_artist);
        $id_title = str_ireplace(array('Nex1Music.IR','Nex1Music','Nex1Musi','Nex1'), "Hobby9.com/Radio", $id_title);
        $id_comment = str_ireplace(array('Nex1Music.IR','Nex1Music','Nex1Musi','Nex1'), "Hobby9.com/Radio", $id_comment);
        $id_album = str_ireplace(array('Nex1Music.IR','Nex1Music','Nex1Musi','Nex1'), "Hobby9.com/Radio", $id_album);

        $getID3->setOption(array('encoding'=>$TextEncoding));
        $tagwriter->filename = $file_path;
        $tagwriter->tagformats = array('id3v1', 'id3v2.3');

        // set various options (optional)
        $tagwriter->overwrite_tags    = true;  // if true will erase existing tag data and write only passed data; if false will merge passed data with existing tag data (experimental)
        $tagwriter->remove_other_tags = false; // if true removes other tag formats (e.g. ID3v1, ID3v2, APE, Lyrics3, etc) that may be present in the file and only write the specified tag format(s). If false leaves any unspecified tag formats as-is.
        $tagwriter->tag_encoding      = $TextEncoding;

        // populate data array
        $TagData = array(
        	'title'  => array($id_title),
          'artist'  => array($id_artist) ,
          'comment' => array($id_comment) ,
          'album' => array($id_album) ,
        );
        $tagwriter->tag_data = $TagData;

        // write tags
        if ($tagwriter->WriteTags()) {
        	echo 'Successfully wrote tags<br>';
        	if (!empty($tagwriter->warnings)) {
        		echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
        	}
        } else {
        	echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
        }
        ##########################################################################
      }
      elseif($this->attachInfo['mine'] == 'YES')
      {
        $media->ispublished = 9;
      }
      else
      {
        $media->ispublished = 2;
      }
    }
    else
    {
      $media->class = Auth::user()->hash;
      $media->ispublished = 2;
    }
    if(array_key_exists('title', $this->attachInfo))
    {
      $media->title = $this->attachInfo['title'];
      if(array_key_exists('tags', $this->attachInfo))
      {
        $media->tags = $this->attachInfo['tags'];
      }
      else {
        $output = preg_replace('!\s+!', ' ', $media->title);
        $output = str_replace(" ","-",trim($output));
        $media->tags = $output;
      }
    }
    if(array_key_exists('des', $this->attachInfo))
    {
      $media->explenation = $this->attachInfo['des'];
    }
    if(array_key_exists('branch', $this->attachInfo))
    {
      $media->branch = $this->attachInfo['branch'];
    }
    if(array_key_exists('publisher', $this->attachInfo))
    {
      $media->publisher = $this->attachInfo['publisher'];
    }
    if(array_key_exists('author', $this->attachInfo))
    {
      $media->creator = $this->attachInfo['author'];
    }
    if(array_key_exists('img', $this->attachInfo))
    {
      $directory = storage_path('app/public/'.config('co.MediaDir'));
      $directory .= '/'.$this->token.'/'.$this->hash.'/';
      $cmd = "mv ".storage_path('app/'.config('co.tmpFileDir')).'/'.$this->fileHash.'_small.jpg'." ".$directory.'/'.$this->hash.'_small.jpg';
      echo PHP_EOL."CMD : ".$cmd.PHP_EOL;
      shell_exec($cmd);

      $cmd = "mv ".storage_path('app/'.config('co.tmpFileDir')).'/'.$this->fileHash.'_larg.jpg'." ".$directory.'/'.$this->hash.'_larg.jpg';
      echo PHP_EOL."CMD1 : ".$cmd.PHP_EOL;
      shell_exec($cmd);
    }
    $media->path = $this->token;


    $media->ip = $_SERVER['REMOTE_ADDR'];

    ##############
    ################
    if(!$haveConvert)#if do not need convert
    {
      if($media->type == 3)
  		{
  			$media->pagetime = MediaSaveHelperClass::returnMp3Duration($this->filePath);
  		}
  		elseif($media->type == 2)
  		{
  			$media->pagetime = MediaSaveHelperClass::returnPdfPage($this->filePath);
  			MediaSaveHelperClass::makePdfThumb($this->fileJustPath,$this->ext);
  		}
  		elseif($media->type == 1)
  		{
  			$media->pagetime = MediaSaveHelperClass::returnVideoDuration($this->filePath);
  			MediaSaveHelperClass::makeVideoThumb($this->fileJustPath,$this->ext);
  		}
  		$media->volume = MediaSaveHelperClass::mediaSize($this->filePath);
      $media->filetype = $this->ext;
    }
    else#if need convert
    {
      $media->pagetime = 'Converting . . .';
      $media->volume = 0;
    }
    $media->save();

    #for telegram channel
    if($media->class == config('co.radio_user') && !$haveConvert)
    {
      TeleSend::sendMusic($media->hash);
    }

    return true;
  }
}
 ?>
