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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:correctingsno')
            ->withoutOverlapping()
            ->runInBackground()
//            ->onOneServer() //need cache driver, more info at : https://laravel.com/docs/5.7/scheduling
            ->hourly()
            ->appendOutputTo(storage_path('logs/CorrectingSNo.log'));

//        $schedule->command('command:pushdatatofirestore')
//            ->withoutOverlapping()
//            ->runInBackground()
//            ->daily()
//            ->appendOutputTo(storage_path('logs/PushDataToFirestore.log'));

//        $schedule->command('command:gathereurobookingsberlindata')
//            ->withoutOverlapping()
//            ->runInBackground()
//            ->daily()
//            ->appendOutputTo(storage_path('logs/GatherEurobookingsBerlinData.log'));
//
//        $schedule->command('command:gathereurobookingsromedata')
//            ->withoutOverlapping()
//            ->runInBackground()
//            ->daily()
//            ->appendOutputTo(storage_path('logs/GatherEurobookingsRomeData.log'));

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
