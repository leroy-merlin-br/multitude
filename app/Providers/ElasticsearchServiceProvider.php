<?php

namespace App\Providers;

use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            return $app['elasticsearch']->connection();
        });
    }
}
