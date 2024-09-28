<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Files;
use App\Classes\Admin\TeleSend;
use App\Classes\Profile\MediaSaveHelperClass;
use App;
use Twitter;
use File;
use App\CoQueue;
use App\Classes\Admin\DlClass;


class TempCom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobby:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
      \Ratchet\Client\connect('ws://127.0.0.1:5588/jsonrpc')->then(function($conn) {
      $conn->on('message', function($msg) use ($conn)
      {
        $res = json_decode($msg,true);
        var_dump($res);
        #$conn->close();
        #exit;
      });
      $message = array(
        'jsonrpc'=>'2.0',
        'id'=>'qwer',
        'method'=>'aria2.tellActive',
        'params'=>array(
          array("status","totalLength","completedLength","downloadSpeed","errorMessage")
        )
      );
      $conn->send(json_encode($message));
      $message = array(
        'jsonrpc'=>'2.0',
        'id'=>'qwer',
        'method'=>'aria2.tellWaiting',
        'params'=>array(0,10,
          array("status","totalLength","completedLength","downloadSpeed","errorMessage")
        )
      );
      $conn->send(json_encode($message));
      $message = array(
        'jsonrpc'=>'2.0',
        'id'=>'qwer',
        'method'=>'aria2.tellStopped',
        'params'=>array(0,10,
          array("status","totalLength","completedLength","downloadSpeed","errorMessage")
        )
      );
      $conn->send(json_encode($message));

      }, function ($e)
      {
          echo "Could not connect: {$e->getMessage()}\n";
      });
      /*$files = Files::whereIn('hash',function($query){
        $query->select('media_hash')->from('tbl_radio_songs')->orderBy('date','desc');
      })->orderBy('endate','desc')->get();
      $orginalPath ='';
      foreach ($files as $file) {
        $directory = storage_path('app/public/'.config('co.MediaDir'));
        $orginalPath = $directory.'/'.$file->path.'/'.$file->hash.'/'.$file->hash.$file->filetype;
        $file->pagetime = MediaSaveHelperClass::returnMp3Duration($orginalPath);
        $file->save();

      }*/

    }
}
