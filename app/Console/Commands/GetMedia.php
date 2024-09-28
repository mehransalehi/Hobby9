<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetMedia extends Command
{
  private $fetch_init = array("URL"=>'',"C_HEADER"=>0,"C_TIMEOUT"=>0,"C_COOKIESESSION"=>true,"C_REFERER"=>"","C_POSTFIELDS"=>"","C_POST"=>0,"USE_COOKIE"=>false,"USE_TOR"=>false);
  private $branchArray = array(
    "joke"  => 'طنز',
    "all"  => 'none',
    "hekayat" =>'متفرقه',
    "news"  => 'صداوسيما',
    "sher"  => 'عاشقانه',
    "tec"  => 'علمی',
    "computer"  => 'کامپيوتر',
    "music"  => 'جدیدترین موزیک ها',
    "majale"  => 'none',
    "learn"  => 'آموزشی',
    "sokhan"  => 'متفرقه',
    "adult"  => 'طنز',
    "love"  => 'عاشقانه',
  );
  private $botToken = '183518756:AAE5EO6Ss2e2ln7m4nbw2hDECwJd7Ro7ddU';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tele:getmedia {type : Type of file} {message : Caption} {fileid : ID of file} {branch : Branch of media}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is a concatenate between tele code and hobby code.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        var_dump($arguments);
        $message = str_replace("\'", "'", $arguments['message']);
        $message = preg_replace('/[^ \w\n]+|^[\x{0600}-\x{06FF}]*$/u', '', $message);
        if(array_key_exists($arguments['branch'], $this->branchArray))
        {
          $branch = $this->branchArray[$arguments['branch']];
        }
        else {
          $branch = 'none';
        }
        $filePath = $this->returnFilePath($arguments['fileid']);
        $this->downAndSave($message,$filePath,$branch);
    }
    public function downAndSave($title,$path,$branch)
    {
    	$vUrl = "https://api.telegram.org/file/bot".$this->botToken."/$path";
      $user = 'TELEGRAM';


      $temp = explode(".", $path);
      $ext = strtolower(end($temp));
      $ext = '.'.$ext;
      $name = md5(date("YmdHis").rand(1,10000));
      $name = $name . $ext;
      $cmd = 'aria2c -d '.storage_path('app/'.config('co.tmpFileDir')).'/ -o '.$name.' '.$vUrl.'';
      #$cmd = 'wget -O "'.$name.'" "'.$vUrl.'"';
      $mainCmd = "nohup sh -c '$cmd && php ".base_path()."/artisan upload:afterdl \"$name\" \"$user\" \"$title\" \"$branch\"' > /dev/null 2> /dev/null & echo $!";
      echo $mainCmd;
      popen($mainCmd, 'r');
      echo PHP_EOL."DONE".PHP_EOL;
    }
    public function returnFilePath($id)
    {
      $init = $this->fetch_init;
    	$url = "https://api.telegram.org/bot".$this->botToken."/getFile?file_id=".$id;
    	$init["URL"]=$url;
    	$result = $this->fetch_content($init);
    	$rep=json_decode($result,true);
    	if(array_key_exists('file_path', $rep['result']))
    	{
    		$filepath=$rep['result']['file_path'];
    		echo "$$$$$$$$$$$$ $filepath $$$$$$$$$$$$";
    		return $filepath;
    	}
    	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!";
    	print_r($rep);
    	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!";
    	return false;
    }
    public function fetch_content(&$init)
    {
    	#~~~~~~~~~~~~~INITIAL~~~~~~~~~~~~~~~~~~~~
    	$cookieJar = './cookie.txt';
    	$fetch_init = $this->fetch_init;

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
    		$init = $this->fetch_init;
    		echo curl_error($ch);
    		return false;
    	}
    	$init = $this->fetch_init;
    	curl_close($ch);
    	return $content;
    }
}
