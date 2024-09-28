<?php
namespace App\Http;
use App\Classes\jDateTime;
use Illuminate\Support\Facades\Auth;
use App\Messages;

class handyHelpers{

  private static $allowedTokens = array('a', 'b', 'c', 'd', 'e', 'f',
                                         'g', 'h', 'i', 'j', 'k', 'l',
                                         'm', 'n', 'o', 'p', 'r', 's',
                                         't', 'u', 'v', 'x', 'y', 'z',
                                         'A', 'B', 'C', 'D', 'E', 'F',
                                         'G', 'H', 'I', 'J', 'K', 'L',
                                         'M', 'N', 'O', 'P', 'R', 'S',
                                         'T', 'U', 'V', 'X', 'Y', 'Z',
                                         '1', '2', '3', '4', '5', '6',
                                         '7', '8', '9', '0');

  public static function countNewMsg()
  {
    $class = Auth::user()->hash;
    $messages = Messages::where('reciver',$class)->where('is_read','u')->get();
    return count($messages);
  }
  public static function makeTimeString($pgTime)
  {
    $showtime = '';
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
    	$showtime = "$hour:$min:$sec";
    }
    elseif($min != 0)
    {
    	$min = str_pad($min,2,'0', STR_PAD_LEFT);
    	$sec = str_pad($sec,2,'0', STR_PAD_LEFT);
    	$showtime = "$min:$sec";
    }
    else
    {
    	$sec = str_pad($sec,2,'0', STR_PAD_LEFT);
           	$showtime = "00:$sec";
    }
    $showtime = self::ta_persian_num($showtime);
    return $showtime;
  }
  public static function ta_persian_num($string)
  {
    //arrays of persian and latin numbers
    $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $latin_num = range(0, 9);

    $string = str_replace($latin_num, $persian_num, $string);

    return $string;
  }
  public static function MTS($time)
  {
    $time = strtotime($time);
		$jdate = new jDateTime(true, true, 'Asia/Tehran');
		@$sMonth = $jdate->date("F", $time, true, true, 'Asia/Tehran');
		@$sDay = $jdate->date("d", $time, true, true, 'Asia/Tehran');
		@$sYear = $jdate->date("Y", $time, true, true, 'Asia/Tehran');
    return $sDay.'  '.$sMonth.'  '.$sYear;
  }
  public static function MTST($time)
  {
    $time = strtotime($time);
    $jdate = new jDateTime(true, true, 'Asia/Tehran');
    @$sMonth = $jdate->date("F", $time, true, true, 'Asia/Tehran');
    @$sDay = $jdate->date("d", $time, true, true, 'Asia/Tehran');
    @$sYear = $jdate->date("Y", $time, true, true, 'Asia/Tehran');
    @$sTime = $jdate->date("G:i", $time, true, true, 'Asia/Tehran');
    return $sTime.'&nbsp;&nbsp;&nbsp;&nbsp;'.$sDay.'  '.$sMonth.'  '.$sYear;
  }
  public static function makeSize($size)
  {
    if($size < 1)
		{
			$size *= 1024;
			if($size < 1 )
			{
				$size *= 1024;
				$size = round($size,0);
				$size .= ' Bite';
			}
			else
			{
				$size = round($size,0);
				$size .= ' KB';
			}

		}
		else
		{
			$size = round($size,2);
			$size .= ' MB';
		}
    return self::ta_persian_num($size);
  }
  public static function returnRandomToken()
  {
    $arr_index = rand(0, count(self::$allowedTokens) - 1);
    $token = self::$allowedTokens[$arr_index];
    return $token;
  }
  public static function UE($title)
  {
    $title = str_replace(" ","_",$title);
    $title = str_replace(str_split('\\/:*?"<>|'), ' ', $title);
    $title = urlencode($title);
    return $title;
  }
  public static function currentDay()
  {
    $jdate = new jDateTime(true, true, 'Asia/Tehran');
    $cDay = $jdate->date("N");
	  $cDay = self::fa_num_to_en($cDay);
    return $cDay;
  }
  public static function dayOfMonth($en=true)
  {
    $jdate = new jDateTime(true, true, 'Asia/Tehran');
    $cDay = $jdate->date("j");
    if($en)
		  $cDay = self::fa_num_to_en($cDay);
    return $cDay;
  }
  public static function fa_num_to_en($string)
  {
	    $persian1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
	    $persian2 = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
	    $num = range(0, 9);
	    $string=str_replace($persian1, $num, $string);
	    return str_replace($persian2, $num, $string);
	}
  public static function createIntervalString($date)
	{
    $time = strtotime($date);
    $now  = strtotime(date("Y-m-d H:i:s"));
    $diff = $time - $now;

		if($diff < 0)
			$diff *= -1;
		if($diff < 60)
		{
			$time = $diff.' ثانیه قبل';
		}
		elseif($diff >= 60 && $diff < 3600)
		{
			$diff = intval($diff/60);
			$time = $diff.' دقیقه قبل';
		}
		elseif($diff >= 3600 && $diff < 86400)
		{
			$diff = intval($diff/3600);
			$time = $diff.' ساعت قبل';
		}
		elseif($diff >= 86400 && $diff < 2592000)
		{
			$diff = intval($diff/86400);
			$time = $diff.' روز قبل';
		}
		elseif($diff >= 2592000 && $diff < 31104000)
		{
			$diff = intval($diff/2592000);
			$time = $diff.' ماه قبل';
		}
		elseif($diff >= 31104000)
		{
			$diff = intval($diff/31104000);
			$time = $diff.' سال قبل';
		}
		return $time;
	}
  public static function isTele($pgTime)
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
			return true;
		}
		elseif($min != 0)
		{
			return true;
		}
		else
		{
			if($sec >= 40)
				return true;
		}
		return false;
	}
}
 ?>
