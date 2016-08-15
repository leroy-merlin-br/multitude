<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use MongoDB\BSON\ObjectID;
use Mongolid\Connection\Pool;
use stdClass;
use Exception;

class MigrationCommand extends Command
{
    /**
     * Command Name.
     * @var string
     */
    protected $name = 'migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database migrations';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate {--rollback : Run migrations in reverse} {--steps=999 : How many migration steps to perform}';

    /**
     * Database instance that will be injected into migrations.
     *
     * @var MongoDB\Database
     */
    protected $db;

    /**
     * Filesystem instance.
     *
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Constructs command and injects dependencies.
     *
     * @param Pool $connPoll
     */
    public function __construct(Pool $connPool, Filesystem $filesystem)
    {
        parent::__construct();

        $conn = $connPool->getConnection();
        $this->db = $conn->getRawConnection()->{$conn->defaultDatabase};
        $this->filesystem = $filesystem;
    }

    public function fire()
    {
        $migrations = $this->filesystem->files($this->laravel->path().'/../database/migrations');
        sort($migrations);

        if ($this->option('rollback')) {
            $this->migrateDown($migrations, $this->option('steps'));
            return;
        }

        $this->migrateUp($migrations, $this->option('steps'));
    }

    /**
     * Migrate up $steps
     *
     * @param  array   $migrations List of migration files
     * @param  integer $steps      Amount of migration files to run
     *
     * @return void
     */
    public function migrateUp($migrations, $steps)
    {
        foreach ($migrations as $filename) {
            if ($steps <= 0) {
                break;
            }

            $migrationName = $this->filesystem->name($filename);
            if ($migrationName > $this->getLastRunnedMigration()) {
                $this->comment("Running $migrationName...");
                $this->runMigration($this->getClassNameFromFilename($migrationName));
                $this->setLastRunnedMigration($migrationName);
                $steps--;
            }
        }
    }

    /**
     * Migrate down $steps
     *
     * @param  array   $migrations List of migration files
     * @param  integer $steps      Amount of migration files to run
     *
     * @return void
     */
    public function migrateDown($migrations, $steps)
    {
        while (count($migrations)) {
            $migrationName = $this->filesystem->name(array_pop($migrations));
            if ($migrationName <= $this->getLastRunnedMigration()) {
                $this->setLastRunnedMigration($migrationName);
                if ($steps <= 0) {
                    return;
                }

                $this->comment("Rolling back $migrationName...");
                $this->runMigration($this->getClassNameFromFilename($migrationName), 'down');

                $steps--;
            }
        }

        $this->setLastRunnedMigration('0');
    }

    /**
     * Figure out the class name by looking at the filename
     *
     * @param  string $filename Name of the migration file
     *
     * @return string Class name
     */
    protected function getClassNameFromFilename($filename)
    {
        preg_match("/[\d_]+(\D+)/", $filename, $matches);

        if (! $matches[1] ?? null) {
            throw new Exception("Unable to resolve class name for migration $filename.", 25);
        }

        return studly_case($matches[1]);
    }

    /**
     * Run the migration
     *
     * @param  string $className Migration class
     * @param  string $direction Method to be called. Usually 'up' or 'down'.
     *
     * @return void
     */
    protected function runMigration($className, $direction = 'up')
    {
        $migrationObject = new $className;
        $migrationObject->$direction($this->db);
    }


    /**
     * Gets the name of the latest runned migration
     *
     * @return string
     */
    protected function getLastRunnedMigration()
    {
        $document = $this->db->migrations->findOne() ?: [];

        return $document->filename ?? '0';
    }

    /**
     * Stores the name of the latest runned migration
     *
     * @param void
     */
    protected function setLastRunnedMigration($migrationName)
    {
        $document = $this->db->migrations->findOne() ?: new stdClass;

        $document->filename = $migrationName;

        $this->db->migrations->replaceOne(
            ['_id' => $document->_id ?? new ObjectID],
            $document,
            ['upsert' => true]
        );
    }
}
