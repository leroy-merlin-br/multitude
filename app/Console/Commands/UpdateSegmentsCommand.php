<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Leadgen\Customer\SegmentParser;
use Leadgen\Segment\Segment;

/**
 * Refresh the Customers of a segment.
 */
class UpdateSegmentsCommand extends Command
{
    /**
     * Command Name.
     *
     * @var string
     */
    protected $name = 'leadgen:segment-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the Customers of a segment.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leadgen:segment-update {--add : Adds customers that match the segment rules} {--remove : Remove customers that don\'t match the segment rules} {segmentslug}';

    /**
     * Injects dependencies
     *
     * @param SegmentParser $segmentParser Segment parser instance.
     */
    public function __construct(SegmentParser $segmentParser)
    {
        parent::__construct();

        $this->segmentParser = $segmentParser;
    }

    /**
     * Performs the command.
     *
     * @return void
     */
    public function fire()
    {
        $segmentSlug = $this->argument('segmentslug');
        $operation = 'add';
        if ($this->option('remove')) {
            $operation = 'remove';
        }

        $this->log("Finding new customers that match '$segmentSlug'...");

        $count = $this->segmentParser->parse(Segment::first(['slug' => $segmentSlug]));

        if ($count < 1) {
            $this->log("No new customer for '$segmentSlug'", 'info');
            return;
        }

        $this->log("$count customers were added to '$segmentSlug'", 'info');
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
