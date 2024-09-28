<?php
namespace App\Classes;


use App\Files;
use App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchClass
{
  public static function group($text)
  {
    $result = Files::where('branch',$text)->where(function($query){
      $query->where('ispublished',1)->orWhere('ispublished',5);
    })->orderBy('id', 'desc')->simplePaginate(config('co.itemSearchPage'));
    return $result;
  }
  public static function search($text,$limit='')
  {
    $query = self::makeQuery($text,$limit);
    $result = DB::select($query);
    return $result;
  }
  public static function externalClassSearch($text,$limit='',$user)
  {
    if(empty($user))
      return false;
    $query = self::makeQuery($text,$limit,false,$user,true);
    $result = DB::select($query);
    return $result;
  }
  public static function classSearch($text,$limit='')
  {
    $query = self::makeQuery($text,$limit,true);
    $result = DB::select($query);
    return $result;
  }
  private static function makeQuery($search_query,$limit,$isClass=false,$user=false,$creator=false)
  {
    $words = self::multiexplode( array("_",".","|","-"," ",",",":") , trim($search_query) );
		$hRabt = config('co.hRabt');
		$searchStr ='';
		$counter = 0;
		$allIsRabt=true;//hame tag ha ya search string harf rabt hastand?
		for($i=0;$i<count($words);$i++)
		{
			if($counter > 8)
				break;
			if(!in_array($words[$i], $hRabt) && strlen($words[$i])>1 OR count($words) == 1)
			{
        #$words[$i] = DB::connection()->getPdo()->quote($words[$i]);
				$searchStr .="*".$words[$i] ."* ";
				$allIsRabt=false;
				$counter++;
			}
		}

		if($allIsRabt)
		{
			for($i=0;$i<count($words);$i++)
			{
				if($counter > 8)
					break;
				$searchStr .="*".$words[$i] ."* ";
				$counter++;
			}
		}
    $creatorS = $limitS = $classQuery = '';
    if(!empty($limit))
    {
      $limitS = " limit $limit";
    }
    if($creator && $user)
    {
      $creatorS = " OR (creator like '%$search_query%' AND  class='$user') ";
    }
    if($isClass)
    {
      $classQuery = " AND ( class='".Auth::user()->hash."' OR hash IN (SELECT target FROM tbl_follow WHERE user='".Auth::user()->hash."' AND type='m') ) " ;
    }
    elseif($user)
    {
      $classQuery = " AND ( class='".$user."') " ;
    }
		$query = "SELECT *, MATCH(title) AGAINST
						(".DB::getPdo()->quote($searchStr)." IN BOOLEAN MODE)
						AS Score FROM tbl_files WHERE MATCH(title) AGAINST
						 (".DB::getPdo()->quote($searchStr)." IN BOOLEAN MODE) AND (ispublished=1 OR ispublished=5)
             $classQuery
             $creatorS
						ORDER BY Score desc,endate desc $limitS";
						#dd($query);
		return $query;
  }
  private static function multiexplode ($delimiters,$string) {

	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}
}
 ?>
