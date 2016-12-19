<?php

namespace App;

use Laravel\Lumen\Application as LumenApplication;

class Application extends LumenApplication
{
    /**
     * Prepare the application to execute a console command.
     *
     * @return void
     */
    public function prepareForConsoleCommand()
    {
        $this->withFacades();

        $this->make('cache');
        $this->make('queue');

        $this->configure('database');

        $this->register('Illuminate\Database\SeedServiceProvider');
        $this->register('Illuminate\Queue\ConsoleServiceProvider');
    }
}
