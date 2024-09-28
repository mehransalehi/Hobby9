<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\Profile\DeepSaveClass;

class DeepSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:deep {hash : File Hash} {ext : File Extention} {path : Full File Path} {user : User Hash} {tmp : Temp File Hash}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deep save info after convert';

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
      DeepSaveClass::deepSave($arguments['hash'],$arguments['ext'],$arguments['path'],$arguments['user'],$arguments['tmp']);
      $this->info("done");
    }
}
