<?php
namespace App\Classes\Profile;

use Illuminate\Support\Facades\Auth;
use App\CoQueue;
use Illuminate\Support\Facades\DB;
################# WORK PLAN ####################
#1- Convert media

class ConvertClass{
  private $fullPath ;
  private $ext;
  private $tmpHash;
  private $fileHash;
  private $class;
  private $ffmpeg_audio_rate = "64k";
	private $bit_rate = "325k";
	private $frame_rate = "30";
  private $returnExt;

  function __construct($fullPath,$ext,$tmpHash,$fileHash)
  {
    $this->fullPath = $fullPath;
    $this->ext = $ext ;
    $this->tmpHash = $tmpHash ;
    $this->fileHash = $fileHash ;
  }
  public function convert()
  {
    if(Auth::check())
    {
      $this->class = Auth::user()->hash;
    }
    else
    {
      $this->class = 'INLINECONV';
    }
    $mainExt = $this->returnMainExt($this->ext); #In here we mapped main ext to convert ext and save it for return to record class to save

    #check queue
    $this->checkNewQueue();
    $this->doConvert();
		return $mainExt;
  }
  public function checkNewQueue()
  {
    $converting = CoQueue::where('status','co')->get();
    if($converting->count() >= config('co.convertQueueSize'))#queue is full
    {
      $rec = new CoQueue;
      $rec->temp_hash = $this->tmpHash;
      $rec->file_hash = $this->fileHash;
      $rec->ext = $this->ext;
      $rec->full_path = $this->fullPath;
      $rec->class = $this->class;
      $rec->date = DB::Raw('NOW()');
      $rec->status = 'qu';
      $rec->save();
    }
    else #queue have empty space and we have new entry
    {
      $wait = CoQueue::where('status','qu')->orderBy('date','desc')->first();
      $rec = new CoQueue;
      $rec->temp_hash = $this->tmpHash;
      $rec->file_hash = $this->fileHash;
      $rec->ext = $this->ext;
      $rec->full_path = $this->fullPath;
      $rec->class = $this->class;
      $rec->date = DB::Raw('NOW()');

      $status=false;
      if(!$wait)
      {
        $rec->status = 'co';
      }
      else
      {
        $rec->status = 'qu';
        #restore oldest record wait to convert
        $this->tmpHash = $wait->temp_hash;
        $this->fileHash = $wait->file_hash;
        $this->ext = $wait->ext;
        $this->fullPath = $wait->full_path;
        $this->class = $wait->user;
        $wait->status = 'co';
        $wait->save();
      }
      $rec->save();
    }

    return true;
  }
  public function popQueue()
  {
    $converting = CoQueue::where('status','co')->get();
    if($converting->count() < config('co.convertQueueSize'))#queue is full
    {
      $count = config('co.convertQueueSize') - $converting->count();
      $wait = CoQueue::where('status','qu')->orderBy('date','desc')->limit($count)->get();
      if($wait->count()>0)
      {
        foreach ($wait as $wa)
        {
          $this->tmpHash = $wa->temp_hash;
          $this->fileHash = $wa->file_hash;
          $this->ext = $wa->ext;
          $this->fullPath = $wa->full_path;
          $this->class = $wa->user;
          $wa->status = 'co';
          $wa->save();
          $this->doConvert();
        }
      }
    }
    return true;
  }
  private function doConvert()
  {
    $class=$this->class;

    $source = storage_path('app/'.config('co.tmpFileDir')).'/'.$this->tmpHash.$this->ext;
    $full_path = $this->fullPath;
    $deepCommand = base_path().'/artisan upload:deep ';
    $mainExt=false;
    switch($this->ext)
		{
      case '.wmv':
      case '.avi':
      case '.mpg':
      case '.3gp':
      case '.mp4':
      case '.flv':
      case '.mov':
      case '.mpeg':
      case '.mkv':
      case '.mts':
        $mainExt = '.mp4';
      	$cmd = config('co.ffmpegPath')." -y -i \"$source\" -filter:v scale=640:360,setsar=1/1 -pix_fmt yuv420p -c:v libx264 -preset:v slow -profile:v baseline -x264opts level=3.0:ref=1 -b:v ".$this->bit_rate." -r:v 25/1 -force_fps -movflags +faststart -c:a libfdk_aac -b:a ".$this->ffmpeg_audio_rate." \"".$full_path.$mainExt."\" && rm -f \"$source\"";
      	$this->cmd = $cmd;
      	$mainCmd = "nohup sh -c '".config('co.ffmpegPath')." -itsoffset -10 -i \"".$source."\" -vcodec mjpeg -qmin 1 -qmax 1 -qscale:v 2 -vframes 1 -an -f rawvideo -s 900x500 \"".$full_path."_larg.jpg\" ; convert \"".$full_path."_larg.jpg\" -resize 250x140\! ".$full_path."_small.jpg ; $cmd && sleep 2 && php $deepCommand $this->fileHash $mainExt $full_path $class $this->tmpHash' > /dev/null 2> /dev/null & echo $!";

                echo PHP_EOL."MAIN : ".$mainCmd.PHP_EOL;
      break;
      case '.ogg':
      case '.aac':
      case '.wav':
      case '.ac3':
      	$mainExt = '.mp3';
      	$cmd = config('co.ffmpegPath')." -y -i \"$source\" -b:v ".$this->bit_rate."  -b:a ".$this->ffmpeg_audio_rate." -acodec libmp3lame \"".$full_path.$mainExt."\" && rm -f \"$source\"";
      	$this->cmd = $cmd;
      	$mainCmd = "nohup sh -c '$cmd && sleep 2 && php $deepCommand $this->fileHash $mainExt $full_path $class $this->tmpHash' > /dev/null 2> /dev/null & echo $!";



      break;

		}
		popen($mainCmd, 'r');

		return $mainExt;
  }
  public function returnMainExt($ext)
  {
    switch($ext)
		{
      case '.wmv':
      case '.avi':
      case '.mpg':
      case '.3gp':
      case '.mp4':
      case '.flv':
      case '.mov':
      case '.mpeg':
      case '.mkv':
      case '.mts':
        $mainExt = '.mp4';
      break;
      case '.ogg':
      case '.aac':
      case '.wav':
      case '.ac3':
      	$mainExt = '.mp3';
      break;

		}
    return $mainExt;
  }
}
 ?>
