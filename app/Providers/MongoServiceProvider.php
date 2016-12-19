<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mongolid\Connection\Connection;
use Mongolid\Connection\Pool;
use Mongolid\Container\Ioc as MongolidIoc;
use Mongolid\Event\EventTriggerService;
use Mongolid\Util\CacheComponent;
use Mongolid\Util\CacheComponentInterface;

/**
 * Provides connection with MongoDB database.
 */
class MongoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the mongoLid driver in auth AuthManager.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConnector();
    }

    /**
     * Register MongoDbConnector within the application.
     *
     * @return void
     */
    public function registerConnector()
    {
        $config = $this->app->make('config');
        MongolidIoc::setContainer($this->app);

        $connectionString = $this->buildConnectionString();
        $connection = new Connection($connectionString);
        $pool = new Pool();
        $eventService = new EventTriggerService();

        $pool->addConnection($connection);
        $this->app->instance(Pool::class, $pool);
        $this->app->instance(EventTriggerService::class, $eventService);
        $this->app->instance(CacheComponentInterface::class, (new CacheComponent()));

        $connection->defaultDatabase = $config
            ->get('database.mongodb.default.database', 'mongolid');
    }

    /**
     * Builds the connection string based in the laravel's config/database.php
     * config file.
     *
     * @return string The connection string
     */
    protected function buildConnectionString()
    {
        $config = $this->app->make('config');

        if (!$result = $config->get('database.mongodb.default.connectionString')) {

            // Connection string should begin with "mongodb://"
            $result = 'mongodb://';

            // If username is present, append "<username>:<password>@"
            if ($config->get('database.mongodb.default.username')) {
                $result .=
                    $config->get('database.mongodb.default.username').':'.
                    $config->get('database.mongodb.default.password', '').'@';
            }

            // Append "<host>:<port>/<database>"
            $result .=
                $config->get('database.mongodb.default.host', '127.0.0.1').':'.
                $config->get('database.mongodb.default.port', 27017).'/'.
                $config->get('database.mongodb.default.database', 'mongolid');
        }

        return $result;
    }
}
