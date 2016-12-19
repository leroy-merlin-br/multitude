<?php

namespace Leadgen\Interaction;

use Leadgen\Base\RepositoryInterface;
use Leadgen\Base\ResourceRepository;
use Mongolid\ActiveRecord;
use Mongolid\Cursor\CursorInterface;

/**
 * Class Repository.
 *
 * The Repository service is responsible for abstracting database queries
 * regarding Interaction in order to have cleaner controllers and a better
 * code-reuse.
 */
class Repository implements RepositoryInterface
{
    /**
     * The entity that the repository manipulates.
     *
     * @var ActiveRecord
     */
    protected $resource = Interaction::class;

    /**
     * ResourceRepository used in composition.
     *
     * @var ResourceRepository
     */
    protected $resourceRepo;

    /**
     * Constructs a new Repository using a ResourceRepository in the composition.
     */
    public function __construct()
    {
        $this->resourceRepo = app(ResourceRepository::class, [$this->resource]);
    }

    /**
     * Retrieves all resources with support to pagination.
     *
     * @param int $page    Page number being displayed.
     * @param int $perPage Results per page.
     *
     * @return CursorInterface
     */
    public function all(int $page = 1, int $perPage = 10): CursorInterface
    {
        return $this->resourceRepo->all($page, $perPage);
    }

    /**
     * Retrieves the resources that maches the query. Supports pagination.
     *
     * @param int $page    Page number being displayed.
     * @param int $perPage Results per page.
     *
     * @return CursorInterface
     */
    public function where($query = [], int $page = 1, int $perPage = 10): CursorInterface
    {
        return $this->resourceRepo->where($query, $page, $perPage);
    }

    /**
     * Find an resource that exists.
     *
     *
     * @param mixed $id Id of the resource to be found.
     *
     * @throws ModelNotFoundException If no document was found.
     *
     * @return ActiveRecord
     */
    public function findExisting($id)
    {
        return $this->resourceRepo->findExisting($id);
    }

    /**
     * Creates a new resource based in the given $data. In case of failure
     * the errors can be retrieved calling 'getLastErrors'.
     *
     * @param array $data Resource attributes.
     *
     * @return ActiveRecord|null resource in case of success or false on failure
     */
    public function createNew(array $data)
    {
        return $this->resourceRepo->createNew($data);
    }

    /**
     * Updated the given resource based in $data. In case of failure the
     * errors can be retrieved calling 'getLastErrors'.
     *
     * @param ActiveRecord $entity Instance being updated.
     * @param array        $data   Resource attributes.
     *
     * @return bool Success
     */
    public function updateExisting(ActiveRecord $entity, array $data): bool
    {
        return $this->resourceRepo->updateExisting($entity, $data);
    }

    /**
     * Updated the given resource based in $data. In case of failure the
     * errors can be retrieved calling 'getLastErrors'.
     *
     * @param ActiveRecord $entity Instance being updated.
     *
     * @return bool Success
     */
    public function deleteExisting(ActiveRecord $entity): bool
    {
        return $this->resourceRepo->deleteExisting($entity);
    }

    /**
     * Retrieves the error of the last operation.
     *
     * @return array
     */
    public function getLastErrors(): array
    {
        return $this->resourceRepo->getLastErrors();
    }

    /**
     * Retrieves interactions that have not ben aknowledged yet.
     *
     * @return CursorInterface
     */
    public function getUnacknowledged(): CursorInterface
    {
        return $this->resourceRepo->where(['acknowledged' => false], 1, 250);
    }
}
