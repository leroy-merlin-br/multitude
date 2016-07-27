<?php
namespace Leadgen\EventType;

use Leadgen\Base\BaseRepository;

/**
 * Class Repository
 *
 * The Repository service is responsible for abstracting database queries
 * regarding EventType in order to have cleaner controllers and a better
 * code-reuse.
 */
class Repository extends BaseRepository
{
    /**
     * The entity that the repository manipulates.
     * @var ActiveRecord
     */
    protected $resource = EventType::class;
}
