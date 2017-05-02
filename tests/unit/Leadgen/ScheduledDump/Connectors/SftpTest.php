<?php

namespace Leadgen\ScheduledDump\Connectors;

use Leadgen\Interaction\Interaction;
use League\Flysystem\Adapter\NullAdapter;
use Mockery as m;
use Mongolid\Cursor\EmbeddedCursor;
use MongoDB\BSON\UTCDateTime;
use PHPUnit_Framework_TestCase;
use TestCase;

class SftpTest extends TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
        m::close();
    }

    public function testShouldImplementConnectorInterface()
    {
        // Arrange
        $connector = new Sftp;

        // Assertion
        $this->assertInstanceOf(ConnectorInterface::class, $connector);
    }

    public function testShouldBeAbleToConfigureConnectorProperly()
    {
        // Arrange
        $connector = new Sftp;

        // Assert
        $connector->configure(['foo' => 'bar', 'filename' => 'rofl.csv']);
        $this->assertAttributeEquals(['foo' => 'bar', 'filename' => 'rofl.csv'], 'settings', $connector);
    }

    public function testShouldDumpInteractions()
    {
        // Arrange
        $connector = new Sftp;
        $interactions = [
            new Interaction,
            new Interaction
        ];
        $interactions[0]->fill(['_id' => 1, 'created_at' => new UTCDateTime(0), 'author' => 'foo@bar.com', 'interaction' => 'did-nothing', 'params' => ['some' => 'json']]);
        $interactions[1]->fill(['_id' => 2, 'author' => 'test@bar.com', 'interaction' => 'did-nothing-too']);
        $interactionsCursor = new EmbeddedCursor(Interaction::class, $interactions);

        $fakeAdapter = new class extends NullAdapter {
            public $settings;
            public $written;

            public function __construct($settings = []) {
                $this->settings = $settings;
            }
            public function write($path, $contents, \League\Flysystem\Config $config)
            {
                $this->written = compact('path', 'contents');
                return parent::write($path, $contents, $config);
            }
        };

        // Act
        $this->setProtected($connector, 'adapterClass', $fakeAdapter);
        $connector->configure(['foo' => 'bar', 'filename' => 'rofl.csv']);
        $result = $connector->dump($interactionsCursor);

        // Assert
        $adapterInstance = $this->getProtected($connector, 'filesystem')->getAdapter();

        $this->assertTrue($result);

        $this->assertEquals(
            ['foo' => 'bar', 'filename' => 'rofl.csv'],
            $adapterInstance->settings
        );

        $this->assertEquals(
            [
                'path' => 'rofl.csv',
                'contents' => "_id;created_at;author;authorId;interaction;channel;location;params\n".
                    "1;1970-01-01T00:00:00+0000;foo@bar.com;;did-nothing;;;{\"some\":\"json\"}\n".
                    "2;;test@bar.com;;did-nothing-too;;;\n"
            ],
            $adapterInstance->written
        );
    }
}
