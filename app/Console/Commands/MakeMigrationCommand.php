<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeMigrationCommand extends Command
{
    /**
     * Command Name.
     * @var string
     */
    protected $name = 'make:migration';

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
    protected $signature = 'make:migration {name}';

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
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function fire()
    {
        $name           = $this->argument('name');
        $migrationsPath = $this->laravel->path().'/../database/migrations/';
        $filename       = date('Y_m_d_His').'_'.$name.'.php';

        $this->filesystem->put($migrationsPath.$filename, $this->renderMigration($name));
        $this->line("<info>Created Migration:</info> $filename");
    }

    protected function renderMigration($name)
    {
        $className = studly_case($name);
        return <<<EOD
<?php

use MongoDB\Database;

/**
 * Base MongoDB migration
 */
class $className
{
    /**
     * Run the migrations
     *
     * @param  Database \$db MongoDB Database
     *
     * @return void
     */
    public function up(Database \$db)
    {
        // @see http://mongodb.github.io/mongo-php-library/classes/collection/#createindex
        // \$db->collectionName->createIndex(['usename' => 1]);
    }

    /**
     * Reverse the migrations
     *
     * @param  Database \$db MongoDB Database
     *
     * @return void
     */
    public function down(Database \$db)
    {
        // \$db->collectionName->dropIndex('usename');
    }
}
EOD;
    }
}
