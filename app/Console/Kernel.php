<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
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
        'App\Console\Commands\RemindUsers',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        try {
            $setting = Setting::first();
        
            $start = Carbon::createFromFormat('Hi', $setting["start_time"])->format('H:i');
            $end = Carbon::createFromFormat('Hi', $setting["end_time"])->format('H:i');

            $schedule->command('remind:users')->dailyAt($start);
            $schedule->command('remind:users')->dailyAt($end);
            
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }
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
