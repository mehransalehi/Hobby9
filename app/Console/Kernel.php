<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DeepSave::class,
        Commands\Convert::class,
        Commands\Afterdl::class,
        Commands\GetMedia::class,
        Commands\GenerateSitemap::class,
        Commands\RadioConfig::class,
        Commands\GetMusic::class,
        Commands\GetVideoMusic::class,
        Commands\TempCom::class,
        Commands\EveryDay::class,
        Commands\Aria::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
