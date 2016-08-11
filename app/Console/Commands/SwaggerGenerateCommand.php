<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Swagger;

class SwaggerGenerateCommand extends Command
{
    /**
     * Command Name.
     * @var string
     */
    protected $name = 'swagger:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates swagger.json based in project annotations';

    /**
     *  Performs the event.
     */
    public function fire()
    {
        $paths = [
            app('path'),
            app('path').'/../Leadgen',
        ];

        $output = Swagger\scan($paths);

        file_put_contents(app('path').'/../public/swagger.json', $output);

        $this->info('swager.json generated successfully');
    }
}
