<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
//use App\Console\Commands\BirthdayDiscount;
//use App\Console\Commands\CustomerNotification;
//use App\Console\Commands\CustomerRedeem;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //App\Console\Commands\BirthdayDiscount::class,
        //App\Console\Commands\CustomerNotification::class,
        //App\Console\Commands\CustomerRedeem::class,
       Commands\BirthdayDiscount::class,
       Commands\CustomerNotification::class,
       Commands\CustomerRedeem::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->call('\App\Http\Controllers\EmailnotificationsController@schedulenotifications')->everyMinute();
       $schedule->command('birthday:discount')->dailyAt('9:00'); 
       $schedule->command('customer:notification')->dailyAt('9:01');
       $schedule->command('customer:redeem')->dailyAt('9:02');
      // $schedule->call('App\Http\Controllers\CronController@calcMonthlyRewards')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
