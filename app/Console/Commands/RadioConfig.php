<?php


#BE creaful in debian base when run a process with nohup . this nohup add 1 to it's pid and run inside commadn with that pid
#BUUUUUT in cent os not like that and first pid return with "echo &!" is the inside pid

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AdminSetting;
use App\Radio;
use App\Files;
use App\Classes\SymlinkClass;

class RadioConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobby:radio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconfigure radio station with icecast and ezstream';

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
        #$pid = shell_exec("icecast -c /usr/local/etc/icecast.xml > /dev/null & echo $!" );
        #echo PHP_EOL.$pid.PHP_EOL;
        $radio = AdminSetting::where('identify','radio_proc')->first();
        if(count($radio) < 1)
        {
          echo PHP_EOL."Radio Not Exist.".PHP_EOL;
          #radio not exist
          #make play list and run ezstream
          $files = Files::whereIn('hash',function($query){
            $query->select('media_hash')->from('tbl_radio_songs')->orderBy('date','desc');
          })->orderBy('endate','desc')->get();
          $orginalPath ='';
          foreach ($files as $file) {
            $directory = storage_path('app/public/'.config('co.MediaDir'));
            $orginalPath .= $directory.'/'.$file->path.'/'.$file->hash.'/'.$file->hash.$file->filetype.PHP_EOL;
          }
          file_put_contents(config('co.ezstream').'playlist.txt', $orginalPath);
          echo "Playlist Made And Stream Run.".PHP_EOL;
          $cmd = "nohup sh -c '/usr/local/bin/ezstream -c ".config('co.ezstream')."ezstream_mp3.xml' > /dev/null 2> /dev/null & echo $!";
          $pid = shell_exec($cmd);#debian base need + 1;
          #save new ezstream pid
          $radio = new AdminSetting;
          $radio->identify = 'radio_proc';
          $radio->value = $pid;
          $radio->save();
          echo "Pid Saved.".PHP_EOL;
        }
        else
        {
          #radio exist
          echo PHP_EOL."Radio Exist.".PHP_EOL;
          #make play list and run new ezstream and keep pid
          #make play list and run ezstream
          $files = Files::whereIn('hash',function($query){
            $query->select('media_hash')->from('tbl_radio_songs')->orderBy('date','desc');
          })->orderBy('endate','desc')->get();
          $orginalPath ='';
          foreach ($files as $file) {
            $directory = storage_path('app/public/'.config('co.MediaDir'));
            $orginalPath .= $directory.'/'.$file->path.'/'.$file->hash.'/'.$file->hash.$file->filetype.PHP_EOL;
          }
          echo PHP_EOL."file count : ".count($files).PHP_EOL;
          file_put_contents(config('co.ezstream').'playlist.txt', $orginalPath);

          echo "Playlist Made And New Stream Run.".PHP_EOL;
          $cmd = "nohup sh -c '/usr/local/bin/ezstream -c ".config('co.ezstream')."ezstream_mp31.xml' > /dev/null 2> /dev/null & echo $!";
          echo PHP_EOL."CMD1 : ".$cmd.PHP_EOL;
          $pid = shell_exec($cmd);#debian base need + 1;
          sleep(2);
          #transfer from old stream to new stream
          $URL=url('/').':9000/admin/moveclients?mount=/stream&destination=/stream1';
          echo PHP_EOL."URL1 : ".$URL.PHP_EOL;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$URL);
          curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
          curl_setopt($ch, CURLOPT_USERPWD, config('co.icecast_user_panel').":".config('co.icecast_pass_panel'));
          $result=curl_exec ($ch);
          $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
          curl_close ($ch);

          #echo PHP_EOL."$status_code".PHP_EOL;
          echo PHP_EOL.PHP_EOL."$result".PHP_EOL.PHP_EOL;
          #kill old ezstream
          shell_exec("kill -9 ".$radio->value);
          echo "Old Stream Killed.".PHP_EOL;

          $cmd = "nohup sh -c '/usr/local/bin/ezstream -c ".config('co.ezstream')."ezstream_mp3.xml' > /dev/null 2> /dev/null & echo $!";
          echo PHP_EOL."CND2 : ".$cmd.PHP_EOL;
          $pid1 = shell_exec($cmd);#debian base need + 1;
          sleep(2);

          $URL=url('/').':9000/admin/moveclients?mount=/stream1&destination=/stream';
          echo PHP_EOL."URL2 : ".$URL.PHP_EOL;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$URL);
          curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
          curl_setopt($ch, CURLOPT_USERPWD, config('co.icecast_user_panel').":".config('co.icecast_pass_panel'));
          $result=curl_exec ($ch);
          $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
          curl_close ($ch);
          echo PHP_EOL.PHP_EOL."$result".PHP_EOL.PHP_EOL;
          #kill temp ezstream
          shell_exec("kill -9 ".$pid);
          echo "Temp Stream Killed.".PHP_EOL;

          echo "Transferd".PHP_EOL;
          #save new ezstream pid
          $radio->value = $pid1;
          $radio->save();
          echo "Pid Saved.".PHP_EOL;
        }
        #$output = shell_exec("ps -fH -U phoenix | grep 'ezstream -c'" );
        #if(strpos($output, 'ezstream -c') !== false)
        #echo $output.PHP_EOL;
    }
}
