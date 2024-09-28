<?php
namespace App\Classes\Admin;
use Htmldom;
use App\Classes\Admin\DlClass;
use DOMDocument;

class YoutubeDL{
  private static $fetch_init = array("URL"=>'',"C_HEADER"=>0,"C_TIMEOUT"=>0,"C_COOKIESESSION"=>true,"C_REFERER"=>"","C_POSTFIELDS"=>"","C_POST"=>0,"USE_COOKIE"=>false,"USE_TOR"=>false);

  public static function getYoutube($text,$ext,$user,$count=10)
  {
    #echo "YES";exit;
    $pageCount = ($count % 20) + 1;
    $key = str_replace(" ", "+", $text);

    $array = array();

    $j = 1;
    for ($i=1; $i <= $pageCount ; $i++)
    {
      $html = new Htmldom("https://www.youtube.com/results?search_query=$key&page=$i");
      foreach($html->find('ol.item-section li a.spf-link') as $element)
      {
        $href = $element->href;
        if(strpos($href,'/watch?v=')>-1 && strpos($href,'/watch?v=') == 0)
    		{
    			if (in_array("https://www.youtube.com".$href, $array)) {
    			    continue;
    			}
    			array_push($array,"https://www.youtube.com".$href);
          self::getYoutubeDirect("https://www.youtube.com".$href,$ext,$user);
          $j++;
          if($j > $count)
          {
            break;
          }
    		}
      }
    }

    return true;
  }
  public static function getYoutubeDirect($text,$ext,$user)
  {
    $title = '';
    $url = self::getDlAndTitle($text, $title);
    $data = array(
      'url' => $url,
      'user' => $user,
      'title' => $title,
      'ext' => $ext
    );
    return DlClass::dlSave($data,true,"YES");
  }
  public static function getLink($text,$ext,$user)
  {
    $title = basename($text,$ext);
    $url = $text;
    $data = array(
      'url' => $url,
      'user' => $user,
      'title' => $title,
      'ext' => $ext
    );
    return DlClass::dlSave($data,true,"YES");
  }
  public static function getDlAndTitle($ad,&$youTubeTitle)
  {
  	//Get video Id
    $init = self::$fetch_init;
  	$yLink = $ad;
  	$yLink = explode("=", $yLink);
  	$elem = count($yLink)-1;
  	$yLink = $yLink[$elem];
  	//~~~~~~~~~~~~~~~~~~~~~~~~
  	$myA='';
  	$ad = str_replace(":","%3A",$ad);
  	$ad = str_replace("/","%2F",$ad);
  	$ad = str_replace("?","%3F",$ad);
  	$ad = str_replace("=","%3D",$ad);
  	$numRet = 3;
  	for($j=0;$j<$numRet;$j++)
  	{
  		$action = "http://www.tubeleecher.com/index.php";
      #$proxy = '127.0.0.1:9050';

  		$init["C_REFERER"] = "http://www.tubeleecher.com/index.php";
      #$init["C_PROXY"] = $proxy;
  		$init["C_POSTFIELDS"] = "yurl=$ad";
  		$init["URL"] = $action;
  		$init["C_COOKIESESSION"] = false;
  		$init["C_TIMEOUT"] = 60;
  		$init["C_HEADER"] = 1;
  		$init["C_POST"] = 1;
  		$init["USE_COOKIE"] = true;

  		if(!($content = self::fetch_content($init)))
  		{
  			echo'ERROR';
  			return false;
  		}
  		$dom = new DOMDocument ( );
  		@$dom->loadHTML ($content);
  		$as = $dom->getElementsByTagName('a');
  		$i = 0;
  		foreach ($as as $a)
  		{
  			$i++;
  			if($i == 3)
  			{
  				$myA = $a->getAttribute ( 'href' );
  				break;
  			}
  		}
  		if(!empty($myA))
  		{
  			break;
  		}
  	}
  	if(empty($myA))
  	{
  		return false;
  	}
  	$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$yLink);
  	parse_str($content, $ytarr);
  	$youTubeTitle = $ytarr['title'];
  	return $myA;
  }
  public static function fetch_content(&$init)
  {
  	#~~~~~~~~~~~~~INITIAL~~~~~~~~~~~~~~~~~~~~
  	$cookieJar = './cookie.txt';

  	if(empty($init["URL"])){$ch = curl_init();}else{$ch = curl_init($init["URL"]);} ##### URL #####
  	if(!$init["C_COOKIESESSION"]){curl_setopt ($ch,CURLOPT_COOKIESESSION,false);} ##### CURLOPT COOKIESESSION #####
  	if(!empty($init["C_REFERER"])){curl_setopt ($ch, CURLOPT_REFERER, $init["C_REFERER"]);} ##### REFERER #####
  	if(!empty($init["C_POSTFIELDS"])){curl_setopt ($ch, CURLOPT_POSTFIELDS, $init["C_POSTFIELDS"]);} ##### POSTFIELDS #####
  	if($init["USE_COOKIE"]){curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieJar);} ##### USE COOKIE #####


  	##### VARIABLE #####
  	if(!empty($init["C_HEADER"])){curl_setopt($ch, CURLOPT_HTTPHEADER,$init["C_HEADER"]);}
  	curl_setopt ($ch, CURLOPT_TIMEOUT, 0);
  	curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieJar);
  	if($init["C_POST"])
  		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

  	##### CONSTANT #####
  	if($init["USE_TOR"])
  	{
  		$proxy_ip = '127.0.0.1';
  		$proxy_port='9050';
  		curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
  		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
  		curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
  	}
      @curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
  	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/21.0");


  	if(!($content = curl_exec($ch)))
  	{
  		echo 'yes';
  		$init = self::$fetch_init;
  		echo curl_error($ch);
  		return false;
  	}
  	$init = self::$fetch_init;
  	curl_close($ch);
  	return $content;
  }
}

 ?>
