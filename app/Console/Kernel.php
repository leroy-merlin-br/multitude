<?php

namespace App\Console;

use App\Console\Commands\MakeMigrationCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\ProcessInteractionsCommand;
use App\Console\Commands\SwaggerGenerateCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SwaggerGenerateCommand::class,
        ProcessInteractionsCommand::class,
        MigrationCommand::class,
        MakeMigrationCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('leadgen:proc-interaction')
            ->everyMinute()
            ->withoutOverlapping();
    }
}
