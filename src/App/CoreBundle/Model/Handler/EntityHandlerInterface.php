<?php

namespace App\CoreBundle\Model\Handler;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface EntityHandlerInterface.
 */
interface EntityHandlerInterface
{
    /**
     * @param $id
     *
     * @return mixed
     */
    public function getEntity($id);

    /**
     * @param array $filters
     *
     * @return mixed
     */
    public function getEntityBy(array $filters = []);

    /**
     * @param array $filters
     * @param array $sorting
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getEntities(array $filters = [], array $sorting = [], $limit = 5, $offset = 0);

    /**
     * @return mixed
     */
    public function createEntity();

    /**
     * @param mixed $entity
     * @param Request $request
     * @return bool
     * @internal param Request $request
     */
    public function saveEntity($entity, Request $request = null);

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function removeEntity($entity);

    /**
     * @param array $entities
     * @param array $schema
     *
     * @return array
     *
     * @internal param array $options
     */
    public function hashEntities(array $entities = [], array $schema = ['id' => 'name']);
}
