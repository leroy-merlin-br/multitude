<?php

namespace App\Console;

use App\Console\Commands\DumpInteractionsCommand;
use App\Console\Commands\MakeMigrationCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\ProcessInteractionsCommand;
use App\Console\Commands\SearchIndexCommand;
use App\Console\Commands\SwaggerGenerateCommand;
use App\Console\Commands\UpdateSegmentsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Leadgen\Segment\Repository;

/**
 * Register and schedule artisan commands.
 */
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
        DumpInteractionsCommand::class,
        UpdateSegmentsCommand::class,
        MigrationCommand::class,
        MakeMigrationCommand::class,
        SearchIndexCommand::class,
    ];

    /**
     * Caches the segments that are being used to register commands
     * @var \Mongolid\Cursor\CursorInterface
     */
    protected $segments;

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule Lumen scheduler.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('leadgen:proc-interaction')
            ->everyMinute()
            ->withoutOverlapping();

        // Schedules every Segment to be updated
        $this->scheduleMany($schedule, $this->getCommandsToSchedule());
    }

    /**
     * Schedules many commands.
     *
     * @param  Schedule $schedule Lumen scheduler.
     * @param  array    $commands Array where the key is the command being scheduled and the value is a cron string.
     *
     * @return void
     */
    protected function scheduleMany(Schedule $schedule, array $commands)
    {
        foreach ($commands as $commandToRun => $cron) {
            if ($cron) {
                $schedule->command($commandToRun)
                    ->cron($cron)
                    ->withoutOverlapping();
            }
        }
    }

    /**
     * Returns an associative array where each command has a cron value of
     * when it should be executed
     *
     * @return array
     */
    protected function getCommandsToSchedule()
    {
        $commands = [];

        foreach ($this->getSegments() as $segment) {
            $commands["leadgen:segment-update --add $segment->slug"] = $segment->additionInterval;
            // $commands["leadgen:segment-update --remove $segment->slug"] = $segment->removalInterval;
        }

        return $commands;
    }

    /**
     * Returns a cursor containing all segments
     * @return \Mongolid\Cursor\CursorInterface
     */
    protected function getSegments()
    {
        if (! $this->segments) {
            $this->segments = app()->make(Repository::class)->all(1, 300);
        }

        return $this->segments;
    }
}
