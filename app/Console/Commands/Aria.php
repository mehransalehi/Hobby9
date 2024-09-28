<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Aria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobby:aria {url : Download file url} {name : Name of file (hash)} {user : User hash} {title : Title of media} {branch : Branch of media}
     {publisher? : Media Publisher} {author? : Media Author} {img? : Music Custom Pic} {mine? : is file upload in webmaster}
     {userdl? : Is this download from user profile?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a download link to a queue of aria2c donwload manager.';
    private $gid = "none";

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

        $arguments = $this->arguments();
        $name = $arguments['name'];
        $user = $arguments['user'];
        $title = $arguments['title'];
        $branch = $arguments['branch'];
        $publisher = $arguments['publisher'];
        $author = $arguments['author'];
        $img = $arguments['img'];
        $isMine = $arguments['mine'];
        $userDl = @$arguments['userdl'];

      $conn->on('message', function($msg) use ($conn)
      {
          $rec = json_decode($msg,true);
          if(array_key_exists("method", $rec))
          {
            if($rec['method'] == "aria2.onDownloadComplete")
            {
              $arguments = $this->arguments();
              $name = $arguments['name'];
              $user = $arguments['user'];
              $title = $arguments['title'];
              $branch = $arguments['branch'];
              $publisher = $arguments['publisher'];
              $author = $arguments['author'];
              $img = $arguments['img'];
              $isMine = $arguments['mine'];
              $userDl = @$arguments['userdl'];

              echo PHP_EOL."COMPLETED".PHP_EOL;
              $mainCmd = "nohup sh -c 'php ".base_path()."/artisan upload:afterdl \"$name\" \"$user\" \"$title\" \"$branch\" \"$publisher\" \"$author\" \"$img\" \"$isMine\" \"$userDl\"' > /dev/null 2> /dev/null & echo $!";
              echo PHP_EOL.$mainCmd.PHP_EOL;
              popen($mainCmd, 'r');
              $conn->close();
            }
            elseif($rec['method'] == "aria2.onDownloadStart")
            {
              echo PHP_EOL."STARTED".PHP_EOL;
            }
          }
          elseif(array_key_exists("error", $rec))
          {
            echo PHP_EOL."ERROR".PHP_EOL;
          }
          elseif(array_key_exists("result", $rec))
          {
            echo "RESULT AND RETURN";
            echo $rec['result'];
          }
          echo "method : ".$rec['method'];
          var_dump($rec);

      });
      $temp = explode(".", $arguments['name']);
      var_dump($arguments);
      var_dump($temp);
      $hash = $temp[0];
      $hash = substr($hash, 0,strlen($hash)/2);
      $message = array(
        'jsonrpc'=>'2.0',
        'id'=>'qwer',
        'method'=>'aria2.addUri',
        'params'=>array(
          array($arguments['url']),
          array(
            "dir"=>storage_path('app/'.config('co.tmpFileDir')),
            "out"=>$arguments['name'],
            "gid"=>$hash
          )
        )
      );
      $conn->send(json_encode($message));
      }, function ($e)
      {
          echo "Could not connect: {$e->getMessage()}\n";
      });
      echo PHP_EOL."DONE".PHP_EOL;
    }

}
