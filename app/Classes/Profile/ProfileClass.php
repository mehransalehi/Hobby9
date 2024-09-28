<?php
namespace App\Classes\Profile;
use App\Classes\jDateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes;
use App\Files;

class ProfileClass {
  public static function returnUserStatChart()
  {

    #$visit = '000125000587000148000254000548000254000698000325000785000124000895000147000235000478000325000785000965000147000258000354000698000148000125000365000148000125000987000415000245000365000875';
    $visit = Auth::user()->visit;
    if(empty($visit))
    	$visit = '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000';

    #echo $visit.PHP_EOL;
    $numbers = str_split($visit, 6);
    $numbers = array_map(function($v) { return ltrim($v, '0'); }, $numbers);
    $cDay = \App\Http\handyHelpers::dayOfMonth(true);
    $mainEntry = array();
    for ($i=$cDay,$j=30; $i < count($numbers) ; $i++,$j--) {
      $entry['date']= self::dateBefor($j);
      $entry['value']= $numbers[$i];
      $mainEntry[]=$entry;
    }
    for ($i=0,$j=$cDay-1; $i < $cDay ; $i++,$j--) {
      $entry['date']= self::dateBefor($j);
      $entry['value']= $numbers[$i];
      $mainEntry[]=$entry;
    }
    $mainEntry = json_encode( $mainEntry );
    return $mainEntry;
  }
  public static function returnMediaStatChart()
  {
    $class=Auth::user()->hash;
    $query = "SELECT type,filetype,count(*) AS filecount,COALESCE(sum(d1),0) AS day0,COALESCE(sum(d2),0) AS day1,COALESCE(sum(d3),0) AS day2,
    COALESCE(sum(d4),0) AS day3,COALESCE(sum(d5),0) AS day4,COALESCE(sum(d6),0) AS day5,COALESCE(sum(d7),0) AS day6 FROM tbl_files
     WHERE class='$class' GROUP BY filetype;";

     $stat  = DB::select($query);

     $cDay = \App\Http\handyHelpers::currentDay();
     $mainEntry = array();
     $mainEntry1 = array();
     $mainEntry['video'] = $mainEntry['ebook'] =$mainEntry['music'] = 0;
     for ($i=0; $i <7 ; $i++) {
       $videoEntry['day'.$i]=0;
       $ebookEntry['day'.$i]=0;
       $musicEntry['day'.$i]=0;
     }
     foreach ($stat as $value)
     {
       $value = (array)$value;
       if($value['type'] == 1)
       {
         $videoEntry = $value;
         $mainEntry['video'] = $value['filecount'];
       }
       elseif($value['type'] == 2)
       {
         $ebookEntry = $value;
         $mainEntry['ebook'] = $value['filecount'];
       }
       else
       {
         $musicEntry = $value;
         $mainEntry['music'] = $value['filecount'];
       }
     }

     for ($i=$cDay,$j=6; $i < 7 ; $i++,$j--) {
       $entry['date']= self::dateBefor($j);
       $entry['video']= $videoEntry['day'.$i];
       $entry['ebook']= $ebookEntry['day'.$i];
       $entry['music']= $musicEntry['day'.$i];
       $mainEntry1[]=$entry;
     }
     for ($i=0,$j=$cDay-1; $i < $cDay ; $i++,$j--) {
       $entry['date']= self::dateBefor($j);
       $entry['video']= $videoEntry['day'.$i];
       $entry['ebook']= $ebookEntry['day'.$i];
       $entry['music']= $musicEntry['day'.$i];
       $mainEntry1[]=$entry;
     }
     $mainEntry['entry'] = json_encode( $mainEntry1 );
     return $mainEntry;
  }
  public static function dateBefor($num)
  {
    $jdate = new jDateTime(true, true, 'Asia/Tehran');
    $agoDays = -1 * $num;
  	$time = strtotime("$agoDays days");
  	$date = $jdate->date("F/d", $time, true, true, 'Asia/Tehran');

    return $date;
  }
  public static function returnTopMedia()
  {
    $class=Auth::user()->hash;
    $top=Auth::user()->top_media;

    $top = Files::where('hash',$top)->where('class',$class)->first();
    return $top;
  }
}
 ?>
