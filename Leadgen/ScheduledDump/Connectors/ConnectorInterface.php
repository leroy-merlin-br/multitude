<?php

namespace Leadgen\ScheduledDump\Connectors;

use Mongolid\Cursor\CursorInterface;

interface ConnectorInterface
{
    /**
     * Entry point of the settings associative array.
     *
     * @param  array $settings Key values that are used to configure the connection.
     *
     * @return void
     */
    public function configure(array $settings);

    /**
     * Actually dumps the given interactions to an external resource.
     *
     * @param  CursorInterface $interactions Interactions that are going to be exported.
     *
     * @return void
     */
    public function dump(CursorInterface $interactions);
}
