<?php

use App\Console\Kernel;

class FunctionalTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->runCommand('db:searchindex');
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Asserts if the given content is present in the last response
     * received
     *
     * @param  string $content  Needle
     *
     * @return FunctionalTestCase  Self
     */
    protected function see($content)
    {
        $this->assertContains(
            $content,
            $this->response->getContent(),
            "Couldn't find $content in response content."
        );

        return $this;
    }

    /**
     * Runs an artisan command by it's name
     *
     * @param  string $command Command name
     * @param  array  $params  Command params
     *
     * @return FunctionalTestCase  Self
     */
    protected function runCommand($command, $params = [])
    {
        $this->app->make(Kernel::class)->call($command, $params);

        return $this;
    }

    /**
     * Calls the Refresh API of Elasticsearch. Making indexed documents ready
     * to be queryied.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/2.3/indices-refresh.html
     */
    protected function waitElasticsearchOperations()
    {
        $indiceName = $this->app->make('config')->get('elasticsearch.defaultIndex');

        $this->app->make(\Elasticsearch\Client::class)
            ->indices()->refresh(['index' => $indiceName]);

        usleep(2 * 1000);
    }
}
