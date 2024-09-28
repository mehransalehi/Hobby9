<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Files;

class EveryDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hobby:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command run every day so you can put your staff in here to run daily.';

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
      #Reset This day view counter for files~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      $currentDay = \App\Http\handyHelpers::currentDay();
      $file = Files::query()->update(['d'.$currentDay => 0]);
      #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    }
}
