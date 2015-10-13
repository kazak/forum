<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 15 05 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Restaurant;
use App\CoreBundle\Entity\RestaurantOpeningHour;
use App\CoreBundle\Exception\InvalidFormException;
use App\CoreBundle\Model\Handler\EntityHandler;
use App\CoreBundle\Model\Handler\OpenHoursHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OpenHoursHandlerHandler.
 */
class OpenHoursHandler extends EntityHandler implements OpenHoursHandlerInterface
{
    /**
     * @var array
     */
    private $services = ['in-house', 'take-away', 'delivery', 'visible_delivery'];

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param Container $container
     * @param $entityClass
     * @param $hourClass
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(Container $container, $entityClass, $hourClass, FormFactoryInterface $formFactory)
    {
        parent::__construct($container, $hourClass);

        $this->entityrepository = $this->objectManager->getRepository($entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * @return array
     */
    public function getCurrentRestaurants()
    {
        return $this->entityrepository->findAll();
    }

    /**
     * @param $id
     *
     * @return null|Restaurant
     */
    public function getCurrentRestaurantsByID($id)
    {
        return $this->entityrepository->find($id);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function processDeleteDateAction(Request $request)
    {
        $post = $request->request->all();
        $entity = $this->getEntity($post['id']);

        $this->objectManager->remove($entity);
        $this->objectManager->flush();

        return new Response();
    }

    /**
     * @return RestaurantOpeningHour
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function post(Request $request)
    {
        $holiday = $this->createEntity();

        return $this->processChangeDateAction($request, $holiday);
    }

    /**
     * @param Request $request
     * @return bool|Response
     */
    public function processChangeDateAction(Request $request)
    {
        $post = $request->request->all();

        $newDate = new \DateTime($post['date']);
        $start = new \DateTime($post['date'].' '.$post['start']);
        $finish = new \DateTime($post['date'].' '.$post['finish']);
        $restaurant = $this->getCurrentRestaurantsByID((int) $post['id']);
        $entity = $this->createEntity();

        $entity->setDate($newDate);
        $entity->setOpeningTime($start);
        $entity->setClosingTime($finish);
        $entity->setService($post['service']);
        $entity->setRestaurant($restaurant);
        $entity->setReason($post['reason']);

        $this->objectManager->persist($entity);
        $this->objectManager->flush();

        //because entity hav't id
        $params = [
            'restaurant' => $post['id'],
            'service' => $post['service'],
            'date' => $newDate,
        ];

        $entities = $this->getEntities($params);

        if (!empty($entities)) {
            return new Response($entities[0]->getId());
        } else {
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    public function findHoliday($id)
    {
        return $this->getEntity($id);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function processUpdateAction(Request $request)
    {
        $post = $request->request->all();
        $data = $post['data'];
        $date = date('d-m-y');
        $Restaurant = $this->getCurrentRestaurantsByID($post['id']);

        foreach ($data as $day => $value) {
            $start = $value['start'] ? new \DateTime($date.' '.$value['start']) : new \DateTime();
            $finish = $value['finish'] ? new \DateTime($date.' '.$value['finish']) : new \DateTime();
            $params = [
                'restaurant' => $post['id'],
                'service' => $post['service'],
                'dayOfWeek' => $day,
            ];

            /*
             * @var null|Array
             */
            $entities = $this->getEntities($params);

            /* @var RestaurantOpeningHour $entity */
            if ($entities) {
                $entity = $entities[0];

                $entity->setOpeningTime($start);
                $entity->setClosingTime($finish);

                $this->objectManager->merge($entity);
            } else {
                $entity = $this->createEntity();

                $entity->setOpeningTime($start);
                $entity->setClosingTime($finish);
                $entity->setService($post['service']);
                $entity->setDayOfWeek($day);
                $entity->setRestaurant($Restaurant);

                $this->objectManager->merge($entity);
            }
        }

        $this->objectManager->flush();

        return new Response();
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param $restaurant_id
     * @param $service
     * @param string $datatime
     * @return bool
     */
    public function checkOpenRestaurant($restaurant_id, $service, $datatime)
    {
        $datetimeObject = new \DateTime($datatime);

        $queryBuilder = $this->repository->createQueryBuilder('u')
            ->where('u.restaurant = :restaurant_id')
            ->setParameter('restaurant_id', $restaurant_id)
            ->andWhere('u.service = :service')
            ->setParameter('service', $service)
            ->andWhere('u.date = :date')
            ->setParameter('date', $datetimeObject->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery();

        $entities = $queryBuilder->getResult();

        if (!empty($entities)) {
            return $this->checkHouersOpen($entities[0], $datetimeObject);
        } else {
            $params = [
                'restaurant' => $restaurant_id,
                'service' => $service,
                'dayOfWeek' => $datetimeObject->format('w'),
            ];

            $entities = $this->getEntities($params);
            if (!empty($entities)) {
                return $this->checkHouersOpen($entities[0], $datetimeObject);
            }

            return false;
        }
    }

    /**
     * @param $entity RestaurantOpeningHour
     * @param $datetimeObject \DateTime
     * @return bool
     */
    public function checkHouersOpen($entity, $datetimeObject)
    {
        $start = $entity->getOpeningTime()->setDate($datetimeObject->format('Y'), $datetimeObject->format('m'), $datetimeObject->format('d'));
        $end = $entity->getClosingTime()->setDate($datetimeObject->format('Y'), $datetimeObject->format('m'), $datetimeObject->format('d'));
        if ($datetimeObject >= $start && $datetimeObject <= $end) {
            return true;
        } else {
            return false;
        }
    }
}
