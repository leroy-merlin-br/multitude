<?php

namespace Leadgen\ScheduledDump\Connectors;

use Leadgen\Interaction\Interaction;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Mongolid\Cursor\CursorInterface;
use MongoDB\BSON\UTCDateTime;
use DateTime;

/**
 * The Sftp ScheduledDump connector.
 */
class Sftp implements ConnectorInterface
{
    /**
     * Filesystem adapter to be instantiated
     * @var string
     */
    protected $adapterClass = SftpAdapter::class;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The settings that are going to be used by the filesystem adapter
     * @var string[]
     */
    protected $settings;

    /**
     * The columns of the csv that will be exported
     * @var string[]
     */
    protected $headers = [
        '_id',
        'created_at',
        'author',
        'authorId',
        'interaction',
        'channel',
        'location',
        'params'
    ];

    /**
     * Entry point of the settings associative array.
     *
     * @param  array $settings Key values that are used to configure the connection.
     *
     * @return void
     */
    public function configure(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Actually dumps the given interactions to an external resource.
     *
     * @param  CursorInterface $interactions Interactions that are going to be exported.
     *
     * @return bool Success
     */
    public function dump(CursorInterface $interactions)
    {
        $this->initializeFilesystem();
        $cachedInteractions = $interactions->all();
        $data = implode(';', $this->headers).PHP_EOL;
        $filename = str_replace(
            '<date>',
            date('Ymd'),
            $this->settings['filename'] ?? 'ScheduledDump_<date>.csv'
        );

        foreach ($cachedInteractions as $interaction) {
            $data .= $this->renderInteraction($interaction).PHP_EOL;
        }

        return $this->filesystem->write($filename, $data);
    }

    /**
     * Initializes the filesystem with the given settings
     *
     * @return void
     */
    protected function initializeFilesystem()
    {
        $this->filesystem = new Filesystem(new $this->adapterClass($this->settings));
    }

    /**
     * Renders a csv line with the given interaction
     *
     * @param  Interaction $interaction Interaction to be rendered.
     *
     * @return string The $interaction as a csv line.
     */
    protected function renderInteraction(Interaction $interaction)
    {
        foreach ($this->headers as $attribute) {
            $content = $interaction->$attribute;

            if ($content instanceof UTCDateTime) {
                $content = $content->toDateTime()->format(DateTime::ISO8601);
            }

            if (is_array($content)) {
                $content = json_encode($content);
            }

            $line[] = "$content";
        }

        return implode(';', $line);
    }
}
