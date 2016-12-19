<?php

namespace Leadgen\Base;

use Mongolid\ActiveRecord;
use Mongolid\Cursor\CursorInterface;

/**
 * The repository interface describes the default signature that
 * is expected from an Entity's Repository.
 * It basically abstracts the database interaction of an entity.
 */
interface RepositoryInterface
{
    /**
     * Retrieves all resources with support to pagination.
     *
     * @param int $page    Page number being displayed.
     * @param int $perPage Results per page.
     *
     * @return CursorInterface
     */
    public function all(int $page = 1, int $perPage = 10): CursorInterface;

    /**
     * Retrieves the resources that maches the query. Supports pagination.
     *
     * @param array $query   Mongo query
     * @param int   $page    Page number being displayed.
     * @param int   $perPage Results per page.
     *
     * @return CursorInterface
     */
    public function where($query = [], int $page = 1, int $perPage = 10): CursorInterface;

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
    public function findExisting($id);

    /**
     * Creates a new resource based in the given $data. In case of failure
     * the errors can be retrieved calling 'getLastErrors'.
     *
     * @param array $data Resource attributes.
     *
     * @return ActiveRecord|null resource in case of success or false on failure
     */
    public function createNew(array $data);

    /**
     * Updated the given resource based in $data. In case of failure the
     * errors can be retrieved calling 'getLastErrors'.
     *
     * @param ActiveRecord $entity Instance being updated.
     * @param array        $data   Resource attributes.
     *
     * @return bool Success
     */
    public function updateExisting(ActiveRecord $entity, array $data): bool;

    /**
     * Updated the given resource based in $data. In case of failure the
     * errors can be retrieved calling 'getLastErrors'.
     *
     * @param ActiveRecord $entity Instance being updated.
     *
     * @return bool Success
     */
    public function deleteExisting(ActiveRecord $entity): bool;

    /**
     * Retrieves the error of the last operation.
     *
     * @return array
     */
    public function getLastErrors(): array;
}
