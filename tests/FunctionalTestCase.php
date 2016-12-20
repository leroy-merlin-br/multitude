<?php

class FunctionalTestCase extends Laravel\Lumen\Testing\TestCase
{
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
}
