<?php

/**
 * Created by PhpStorm.
 * User: dss
 * Date: 15.12.15
 * Time: 12:30
 */

namespace CoreBundle\Model\Handler;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class EntityHandler.
 */
class EntityHandler
{
    /*
     * Container
     */
    protected $container;

    protected $entityClass;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var User
     */
    protected $customer;

    /*
     * EntityRepository
     */
    protected $repository;

    /**
     * @param Container $container
     * @param $entityClass
     */
    public function __construct(Container $container, $entityClass)
    {
        $this->container = $container;
        $this->entityClass = $entityClass;

        $this->objectManager = $this->container->get('doctrine')->getManager();
        $this->repository = $this->objectManager->getRepository($this->entityClass);
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getEntity($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param array $filters
     * @return null|object
     */
    public function getEntityBy(array $filters = [])
    {
        return $this->getRepository()->findOneBy($filters);
    }

    /**
     * @param array $filters
     * @param array $sorting
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getEntities(array $filters = [], array $sorting = [], $limit = 500, $offset = 0)
    {
        $entities = $this->getRepository()->findBy($filters, $sorting, $limit, $offset);

        return $entities ?: [];
    }

    /**
     * @param array $filters
     *
     * @return array|null|object
     */
    public function findOne(array $filters = [])
    {
        return $this->getEntityBy($filters);
    }

    /**
     * @return mixed
     */
    public function createEntity()
    {
        return new $this->entityClass();
    }

    /**
     * @param $entity
     * @return bool
     */
    public function saveEntity($entity)
    {
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();

        return true;
    }

    /**
     * @param $entity
     * @return bool
     */
    public function removeEntity($entity)
    {
        $this->getObjectManager()->remove($entity);
        $this->getObjectManager()->flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hashEntities(array $entities = [], array $schema = ['id' => 'name'])
    {
        $map = [];

        if ($this->isNotValidHashData($entities, $schema)) {
            return $map;
        }

        $keyProp = $schema[0];
        $valProp = $schema[1];
        $keyGetter = 'get'.ucfirst($schema[0]);
        $valGetter = 'get'.ucfirst($schema[1]);

        foreach ($entities as $entity) {
            if (!$this->isValidHashEntityStructure($entity, $keyProp, $valProp, $keyGetter, $valGetter)) {
                continue;
            }

            $map[$entity->{$keyGetter}()] = $entity->{$valGetter}();
        }

        return $map;
    }

    /**
     * @param $entity
     */
    public function writeInBase($entity)
    {
        $this->saveEntity($entity);
    }

    /**
     * @param array $entities
     * @param array $schema
     *
     * @return bool
     */
    private function isNotValidHashData($entities, $schema)
    {
        return !$entities || 2 !== count($schema) || !is_string($schema[0]) || !is_string($schema[1]);
    }

    /**
     * @param $entity
     * @param $keyProp
     * @param $valProp
     *
     * @return bool
     */
    private function isHashPropertiesExists($entity, $keyProp, $valProp)
    {
        return property_exists($entity, $keyProp) && property_exists($entity, $valProp);
    }

    /**
     * @param $entity
     * @param $keyGetter
     * @param $valGetter
     *
     * @return bool
     */
    private function isHashMethodsExists($entity, $keyGetter, $valGetter)
    {
        return method_exists($entity, $keyGetter) && method_exists($entity, $valGetter);
    }

    /**
     * @param $entity
     * @param $keyProp
     * @param $valProp
     * @param $keyGetter
     * @param $valGetter
     *
     * @return bool
     */
    private function isValidHashEntityStructure($entity, $keyProp, $valProp, $keyGetter, $valGetter)
    {
        return $this->isHashPropertiesExists($entity, $keyProp, $valProp) && $this->isHashMethodsExists($entity, $keyGetter, $valGetter);
    }

    /**
     * @return User|null
     */
    protected function getCustomer()
    {
        if (isset($this->customer)) {
            return $this->customer;
        }

        if (!$this->container->has('security.token_storage')) {
            return null;
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $this->customer = $user;
    }

    /**
     * @param        $data
     * @param string $entityName
     * @param string|null $code
     *
     * @return array
     */
    protected function getResponse($data, $entityName = null, $code = null)
    {
        if (is_null($code)) {
            $errorCode = !empty($data) ? 200 : 404;
        } else {
            $errorCode = $code;
        }

        $errorMessage = $entityName ? $entityName . ' not found' : null;

        $response = [
            'data' => !empty($data) ? $data : $errorMessage,
            'errorCode' => $errorCode,
        ];

        return $response;
    }
}
