<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Leadgen\ScheduledDump\ScheduledDump;
use Leadgen\ScheduledDump\DumpExecutor;

/**
 * This command does executes an ScheduledDump throught it's Connector in order
 * to make a series of interactions available on external resources.
 */
class DumpInteractionsCommand extends Command
{
    /**
     * Command Name.
     *
     * @var string
     */
    protected $name = 'leadgen:dump-interaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dumps the interactions for the given ScheduledDump.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leadgen:dump-interaction {dumpslug: Slug that identifies the ScheduledDump to be made}';

    /**
     * Injects dependencies
     *
     * @param DumpExecutor $dumpExecutor ScheduledDump parser instance.
     */
    public function __construct(DumpExecutor $dumpExecutor)
    {
        parent::__construct();

        $this->dumpExecutor = $dumpExecutor;
    }

    /**
     * Performs the command.
     *
     * @return void
     */
    public function fire()
    {
        $dumpSlug = $this->argument('dumpslug');

        $this->log("Executing scheduled dump '$dumpSlug'...");

        $count = $this->dumpExecutor->execute(ScheduledDump::first(['slug' => $dumpSlug]));

        if ($count < 1) {
            $this->log("No new interactions written in '$dumpSlug'", 'info');
            return;
        }

        $this->log("$count interactions were dump in '$dumpSlug'", 'info');
    }

    /**
     * Prints output to terminal and to log
     *
     * @param  string $message String to be printed.
     * @param  string $style   Style of the output.
     *
     * @return void
     */
    protected function log(string $message, string $style = "comment")
    {
        $this->laravel->log->info("{$this->name} - $message");
        $this->line("<$style>$message<$style>");
    }
}
