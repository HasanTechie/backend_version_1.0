<?php

namespace App\Console;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (!File::exists(storage_path() . '/mylogs/')) {
            Storage::makeDirectory('/mylogs/');
        }
//        $schedule->command('command:correctingsno')
//            ->withoutOverlapping()
//            ->runInBackground()
////            ->onOneServer() //need cache driver, more info at : https://laravel.com/docs/5.7/scheduling
//            ->twiceDaily()
//            ->appendOutputTo(storage_path('app/mylogs/CorrectingSNoCommand.log'));

        /*
        $schedule->command('command:gatherhrshotels')
            ->withoutOverlapping()
            ->runInBackground()
//            ->onOneServer() //need cache driver, more info at : https://laravel.com/docs/5.7/scheduling
            ->daily()//run once
            ->thursdays()//run only on certain day
            ->appendOutputTo(storage_path('app/mylogs/GatherHrsHotelsCommand' . date("Y-m-d") . '.log'));

        */
        $schedule->command('command:gatherhrsroomsprices')
            ->withoutOverlapping()
            ->runInBackground()
            ->dailyAt('05:12')//run once
            ->mondays()
            ->appendOutputTo(storage_path('app/mylogs/GatherHrsRoomsPricesCommand' . date("Y-m-d") . '.log'));


        $schedule->command('command:gathergooglehrsdata')
            ->withoutOverlapping()
            ->runInBackground()
            ->daily()//run once
            ->appendOutputTo(storage_path('app/mylogs/GatherGoogleHRSDataCommand.log'));

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
