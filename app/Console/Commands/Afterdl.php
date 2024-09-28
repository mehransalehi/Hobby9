<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\Profile\RecordClass;
use App\UploadIden;

class Afterdl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:afterdl {name : Name of file (hash)} {user : User hash} {title : Title of media} {branch : Branch of media}
     {publisher? : Media Publisher} {author? : Media Author} {img? : Music Custom Pic} {mine? : is file upload in webmaster}
     {userdl? : Is this download from user profile?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send information to record class to save media';

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

        $temp = explode(".", $arguments['name']);
        $hash = $temp[0];
        $ext = strtolower(end($temp));
        $ext = '.'.$ext;


        if(!file_exists(storage_path('app/'.config('co.tmpFileDir')).'/'.$hash.$ext))
        {
          $this->info("File not exist");
          exit;
        }
        if(!$this->checkMimes($ext))
        {
          $this->info("File not valid");
          exit;
        }
        $data = array(
          "user"=>$arguments['user'],
          "title"=>$arguments['title'],
          "branch"=>@$arguments['branch'],
          "publisher"=>@$arguments['publisher'],
          "author"=>@$arguments['author'],
          "img"=>@$arguments['img'],
          "mine"=>@$arguments['mine'],
        );
        #var_dump($data);
        $record = new RecordClass($hash,$ext,$hash,$data);
        if(($fileHash = $record->save()))
        {
          $this->info("upload successfuly");
        }
        else
        {
          $this->info("upload faild");
        }
        #save identify
        if($arguments['userdl'] == 'YES')
        {
          $class = $arguments['user'];
          UploadIden::create(["class"=>$class,"hash"=>$hash,"file_hash"=>$fileHash]);
        }
    }
    private function checkMimes($guessExt)
    {
      if(!in_array($guessExt, config('co.allowedExtensions')))
      {
        return false;
      }
      return true;
    }
}
