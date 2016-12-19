<?php

namespace App\Console\Commands;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

class SearchIndexCommand extends Command
{
    /**
     * Command Name.
     *
     * @var string
     */
    protected $name = 'db:searchindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepares elasticsearch to index documents';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:searchindex {--rebuild : Force rebuild of index}';

    /**
     * Elasticsearch client.
     *
     * @var Client
     */
    protected $elasticsearch;

    /**
     * Constructs command and injects dependencies.
     *
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Command execution logic
     */
    public function fire()
    {
        $indiceExists = true;
        $indexName = app('config')->get('elasticsearch.defaultIndex', 'main');
        $params = ['index' => $indexName];

        if (! $this->indiceExists($indexName)) {
            $this->log("index '$indexName' does not exist yet.");
            $indiceExists = false;
        }

        if ($this->option('rebuild') && $indiceExists) {
            $this->log("dropping index '$indexName'...", 'info');
            $this->elasticsearch->indices()->delete($params);
            $indiceExists = false;
        }

        if ($indiceExists) {
            $this->log("index '$indexName' already exists.");
            return;
        }

        $this->log("creating index '$indexName'...");
        $this->elasticsearch->indices()->create($params);
        $this->log("index '$indexName' created successfully.", "info");
    }

    /**
     * Checks if $indexName exists in Elasticsearch
     *
     * @param  string $indexName Name of the index
     *
     * @return bool
     */
    protected function indiceExists(string $indexName): bool
    {
        try {
            $this->elasticsearch->indices()->get(['index' => $indexName]);
        } catch (Missing404Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Prints output to terminal and to log
     *
     * @param  string $message
     */
    protected function log(string $message, $style = "comment")
    {
        $this->laravel->log->info("db:searchindex - $message");
        $this->line("<$style>$message<$style>");
    }
}
