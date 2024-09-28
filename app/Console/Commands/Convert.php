<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Files;
use App\Classes;

class Convert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:fix {type : convert type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converter data';

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
      $type = $arguments['type'];
      if($type == 'files')
      {
        $files = Files::where('ispublished',1)->orWhere('ispublished',5)->get();
        echo "TOTAL: ".count($files).PHP_EOL;
        echo "--------------------------";
        $j = 1;
        foreach ($files as $file)
        {
          $source = '/var/www/myfiles/'.$file->path.'/'.$file->hash.'/*';
          $destination = storage_path('app/public/'.config('co.MediaDir'));
          $destination = $destination.'/'.$file->path.'/'.$file->hash.'/';

          Storage::disk('media')->makeDirectory($file->path.'/'.$file->hash);

          $cmd = "cp $source $destination";
          shell_exec($cmd);
          #echo $j.PHP_EOL;
          $j++;
        }
      }
      else {
        $users = Classes::all();
        echo "TOTAL: ".count($users).PHP_EOL;
        echo "--------------------------";
        $j = 1;
        foreach ($users as $user)
        {
          if(empty($user->pic_path))
            continue;
          $source = '/var/www/myfiles/user_pics/'.$user->pic_path.'/'.$user->hash.'/*';
          $destination = storage_path('app/public/userpic/');
          $destination = $destination.'/'.$user->pic_path.'/'.$user->hash.'/';

          Storage::makeDirectory('public/userpic/'.$user->pic_path.'/'.$user->hash);

          $cmd = "cp $source $destination";
          shell_exec($cmd);
          echo $j;
          for ($i=1; $i <=count((string)$j) ; $i++) {
              echo chr(8);
          }
          $j++;
        }
      }

      $this->info("done");
    }
}
