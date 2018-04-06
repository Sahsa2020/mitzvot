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
        Commands\ResetDailyDepositsAll::class,
        Commands\SendWeeklyEmail::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath = env('SCHEDULE_FILE_OPATH', 'schedule.log');
        /* Reset daily deposit */
        $schedule->command('ResetDailyDepositsAll')
            ->withoutOverlapping()
            ->sendOutputTo($filePath)
            ->daily()
            ->at('23:59');
        /* Send Weekly Email Digest */
        $schedule->command('email:weekly')
            ->withoutOverlapping()
            ->sendOutputTo($filePath)
            ->weekly()
            ->fridays()
            ->at('20:10');
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
