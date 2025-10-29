<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (env('APP_TYPE') == 'Aggregator') {
            $schedule->command('messages:send-scheduled')->everyMinute();
            $schedule->command('sms:test')->everyThirtyMinutes();
            // $schedule->command(
            //     'get:aggregator-dlr-status-update-from-infozillion --batch=1000 --perJob=250'
            // )->everyTwoMinutes()
            //     ->withoutOverlapping(30);
            $schedule->command('outbox:archive')->daily();
        } else { 
            // $schedule->command('retry:process')->everyTenMinutes();
            $schedule->command('send:infozilion-dlr-status-update')->everyMinute();
        }
        $schedule->command('gbarta:fetch-dlr')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\RetryRequestCommand::class,
    ];
}
