<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 19 05 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Restaurant;
use App\CoreBundle\Exception\Restaurant\BadRequestException;
use App\CoreBundle\Exception\Restaurant\NoContentException;
use App\CoreBundle\Form\RestaurantAddressType;
use App\CoreBundle\Form\SeoType;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use App\CoreBundle\Model\Handler\RestaurantHandlerInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\CoreBundle\Entity\RestaurantOpeningHour;

/**
 * Class RestaurantHandler
 * @package App\CoreBundle\Handler
 */
class RestaurantHandler extends EntityCrudHandler implements RestaurantHandlerInterface
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function processIndexAction(Request $request)
    {
        return [
            'entities' => $this->getEntities([], [], null)
        ];
    }

    /**
     * @param $id
     *
     * @return null|Restaurant
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return Restaurant
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function processShowAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        $entity->setDescriptionTitle($request->getLocale());

        if (!$entity) {
            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        return [
            'restaurant' => $entity,
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function processChangePerformanceAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute('app_back_office_restaurant_index');
        }
        $post = $request->request->all();
        $errors = [];

        if (isset($post['timeTakeaway'], $post['timeDelivery'])) {
            $translator = $this->container->get('translator');
            $errorText = $translator->trans('should be', [], 'dolly') . ' ' .
                $translator->trans('integer', [], 'dolly');

            $timeTakeaway = (int)$post['timeTakeaway'];
            $timeDelivery = (int)$post['timeDelivery'];
            $guarantyVoided = isset($post['guarantyVoided']) && $post['guarantyVoided'] === "on" ? 1 : 0;
            if ($timeTakeaway < 20) {
                $errors[] = $translator->trans('Extended takeaway', [], 'dolly') . ' ' .
                    $errorText .
                    $translator->trans('and greater than %val%', ['%val%' => 20], 'dolly');
            }
            if ($timeDelivery < 60) {
                $errors[] = $translator->trans('Extended delivery', [], 'dolly') . ' ' .
                    $errorText .
                    $translator->trans('and greater than %val%', ['%val%' => 60], 'dolly');
            }

            if (0 === count($errors)) {
                $entity->setTimeTakeaway($timeTakeaway);
                $entity->setTimeDelivery($timeDelivery);
                $entity->setWarrantyVoided($guarantyVoided);
                $this->saveEntity($entity);
            }

        }
        return [
            'restaurant' => $entity,
            'errors' => $errors,
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function processUpdateAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        $form = $this->getUpdateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->getAddress()->setRestaurant($entity);

            $this->objectManager->persist($entity);
            $this->objectManager->flush();

            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        return [
            'restaurant' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|RedirectResponse
     */
    public function processDeleteAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        $form = $this->getDeleteForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->objectManager->remove($entity);
            $this->objectManager->flush();

            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        return [
            'restaurant' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function processDisableAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        if (!$entity) {
            return $this->redirectToRoute('app_back_office_restaurant_index');
        }

        $entity->setActive(0);
        $this->objectManager->persist($entity);
        $this->objectManager->flush();

        return $this->redirectToRoute('app_back_office_restaurant_index');
    }

    /**
     * @param Restaurant $entity
     * @throws Exception\AlreadySubmittedException
     * @throws Exception\LogicException
     * @throws Exception\UnexpectedTypeException
     * @return $this|FormInterface
     */
    public function getUpdateForm($entity)
    {
        $form = $this->createForm($this->formName, $entity, [
            'action' => $this->generateUrl('app_back_office_restaurant_update', ['id' => $entity->getId()]),
        ])
            ->add('title')
            ->add('visible', 'choice', [
                'label' => 'Visible on Web',
                'choices' => ['Hidden', 'Visible'],
            ])
            ->add('description', 'ckeditor', [
                'config' => [
                    'toolbar' => $this->getCKEditorToolbarConfig(),
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => ['instance' => 'default'],],
                'label' => 'Description',                     // [4]
            ])
            ->add('address', new RestaurantAddressType($this->container), [
                'label' => 'Restaurant location',
            ]);
        /*if ($this->locale_enabled) {
            $form->add('translations',
                'a2lix_translations', array(
                    'fields' => array(                      // [3]
                        'description' => array(                   // [3.a]
                            'field_type' => 'ckeditor',
                            'config' => [
                                'toolbar' => $this->getCKEditorToolbarConfig(),
                                'filebrowserBrowseRoute' => 'elfinder',
                                'filebrowserBrowseRouteParameters' => ['instance' => 'default'],],
                            'label' => 'description',                     // [4]
                        ),
                    ),
                ));
        } else {
            $form->add('translations',
                'a2lix_translations', array(
                    'label' => 'Context',
                    'locales' => array('no'),
                    'fields' => array(                      // [3]
                        'description' => array(                   // [3.a]
                            'field_type' => 'ckeditor',
                            'config' => [
                                'toolbar' => $this->getCKEditorToolbarConfig(),
                                'filebrowserBrowseRoute' => 'elfinder',
                                'filebrowserBrowseRouteParameters' => ['instance' => 'default'],],
                            'label' => 'description',                     // [4]
                        ),
                    ),
                ));
        }*/

        $form->add('seo', new SeoType($this->container), [
            'label' => 'SEO',
        ])
            ->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * @param Restaurant $entity
     *
     * @return $this|FormInterface
     */
    public function getDeleteForm($entity)
    {
        $form = $this->createForm($this->formName, $entity, [
            'action' => $this->generateUrl('app_back_office_restaurant_delete', ['id' => $entity->getId()]),
        ])
            ->add('submit', 'submit', ['label' => 'Delete']);

        return $form;
    }

    /**
     * @return mixed
     */
    public function getCKEditorToolbarConfig()
    {
        $boConfig = $this->container->getParameter('app_back_office_config');

        return $boConfig['appearance']['ckeditor']['toolbar'];
    }

    /**
     * @param int $restaurantId
     * @param string $service
     * @param int $days
     *
     * @return array
     */
    public function getOpeningHours($restaurantId, $service, $days = 6)
    {
        /** @var EntityManager $manager */
        $manager = $this->container->get('doctrine')->getManager();
        $connection = $manager->getConnection();
        $connection->executeQuery("SET @now = NOW();SET @dayOfWeekNow = IF(DAYOFWEEK(@now) = 1,7,DAYOFWEEK(@now)-1);");
        $statement = $connection->prepare("SELECT * FROM (
                                SELECT
                                  IFNULL(`date`, DATE(@now + INTERVAL (
                                    IF(
                                        `day_of_week` >= @dayOfWeekNow,
                                        `day_of_week` - @dayOfWeekNow,
                                        7 + `day_of_week` - @dayOfWeekNow
                                    )
                                  ) DAY))
                                    AS `date`,
                                  opening_time,
                                  closing_time
                                FROM restaurant_opening_hours
                                WHERE restaurant_id = :restaurantId AND service = :service
                                ORDER BY `date` ASC, day_of_week ASC
                              ) openingHour
                            GROUP BY `date`
                            LIMIT " . (int)$days);

        $statement->bindValue('restaurantId', $restaurantId);
        $statement->bindValue('service', $service);
        $statement->execute();
        $openingHours = $statement->fetchAll();

        $now = new \DateTime();
        $key = count($openingHours);

        for($i=0; $i < $key; $i++) {

            $date = new \DateTime($openingHours[$i]['date']);
            $openingHours[$i]['date_format'] = $date->format('j').
                '. '. $this->norgeMounth($date->format('m'));

            if($now->format('d') == $date->format('d')){
                $openingHours[$i]['date_format'] .= ' (I dag)';
            }
        }

        return $openingHours;
    }

    /**
     * @param $number
     * @return mixed
     */
    public function norgeMounth($number)
    {
        $months = [1 => 'Januar',
            2 => 'Februar',
            3 => 'Mars',
            4 => 'April',
            5 => 'Mai',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'August',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'];

        return $months[(int)$number];
    }

    /**
     * @param Restaurant $restaurant
     * @return mixed
     */
    public function getRestaurantIfOpen($restaurant)
    {
        $openHoursRepository = $this->container->get('doctrine')
            ->getManager()->getRepository('AppCoreBundle:RestaurantOpeningHour');
        $currentDate = new \DateTime();
        $deviatingCriteria = new Criteria();

        $deviatingCriteria->where($deviatingCriteria->expr()->eq('restaurant', $restaurant));
        $deviatingCriteria->andWhere($deviatingCriteria->expr()->eq('date', $currentDate));
        $deviatingCriteria->andWhere($deviatingCriteria->expr()->eq('service', 'delivery'));
        $openHours = $openHoursRepository->matching($deviatingCriteria)->first();

        if ($openHours) {
            return $openHours->getOpeningTime() < $currentDate &&
            $openHours->getClosingTime() > $currentDate ? $restaurant : null;
        } else {
            $criteria = new Criteria();

            $criteria->orWhere($criteria->expr()->eq('dayOfWeek', $currentDate->format('N')));
            $criteria->andWhere($criteria->expr()->eq('restaurant', $restaurant));
            $criteria->andWhere($criteria->expr()->eq('service', 'delivery'));
            $criteria->andWhere($criteria->expr()->gte('closingTime', $currentDate));
            $criteria->andWhere($criteria->expr()->lte('openingTime', $currentDate));

            $openHours = $openHoursRepository->matching($criteria)->first();
            return $openHours ? $restaurant : null;
        }
    }

    /**
     * @param Restaurant $restaurant
     * @param $orderType
     * @return array|null
     */
    public function getMessages(Restaurant $restaurant, $orderType)
    {
        $messages = [];

        if ($extedetTimeMessage = $this->getExtendedTimeMessage($restaurant, $orderType)) {
            $messages['showNextButton'] = $extedetTimeMessage['critical'] ? false : true;
            unset($extedetTimeMessage['critical']);
            $messages[]= $extedetTimeMessage;
        }

        return count($messages) > 0 ? $messages : null;
    }

    /**
     * @param Restaurant $restaurant
     * @param $orderType
     * @return null|array
     * @throws \InvalidArgumentException
     */
    private function getExtendedTimeMessage(Restaurant $restaurant, $orderType)
    {
        $defaultParams = $this->container->getParameter('app_core.restaurant_default_values');
        $translator = $this->container->get('translator');
        $restaurantTakeawayTime = $restaurant->getTimeTakeaway();
        $message['critical'] = false;
        if ($orderType === 'takeaway' &&
            $restaurantTakeawayTime > $defaultParams['takeaway']
        ) {
            $message['title'] = $translator->trans(
                'messages.extendeds.takeaway.title',
                [],
                'dolly'
            );
            $message['body'] = $translator->trans(
                'messages.extendeds.takeaway.body',
                ['%time%' => $restaurantTakeawayTime],
                'dolly'
            );
        } elseif ($orderType === 'delivery' || $orderType === 'hotel') {
            $restaurantDeliveryTime = $restaurant->getTimeDelivery();
            $restaurantWarrantyVoided = $restaurant->getWarrantyVoided();
            if (
                $restaurantDeliveryTime > $defaultParams['delivery'] &&
                $restaurantWarrantyVoided !== $defaultParams['warranty_voided']
            ) {
                $message['title'] = $translator->trans(
                    'messages.extendeds.delivery_and_warranty.title',
                    [],
                    'dolly'
                );
                $message['body'] = $translator->trans(
                    'messages.extendeds.delivery_and_warranty.body',
                    [],
                    'dolly'
                );
            } elseif ($restaurantWarrantyVoided !== $defaultParams['warranty_voided']) {
                $message['title'] = $translator->trans(
                    'messages.extendeds.delivery_warranty.title',
                    [],
                    'dolly'
                );
                $message['body'] = $translator->trans(
                    'messages.extendeds.delivery_warranty.body',
                    [],
                    'dolly'
                );
            } elseif ($restaurantDeliveryTime > $defaultParams['delivery']) {
                $message['title'] = $translator->trans(
                    'messages.extendeds.delivery.title',
                    [],
                    'dolly'
                );
                $message['body'] = $translator->trans(
                    'messages.extendeds.delivery.body',
                    ['%time%' => $restaurantDeliveryTime],
                    'dolly'
                );
            }
        }
        return count($message) > 1 ? $message : false;
    }

    /**
     * @param $longitude
     * @param $latitude
     * @param int $limit
     * @return array
     */
    private function findClosestRestaurants($longitude, $latitude, $limit = 3)
    {
        return array_slice(
            $this->getRestaurantAddressRepository()
                 ->getClosestRestaurants($longitude, $latitude), 0, $limit
        );
    }

    /**
     * @param string $query
     * @return bool
     */
    private function findCoordinatesByPostCodeOrAddressOrCity($query)
    {
        if ($postCode = $this->getPostCodeRepository()->search($query)) {
            return [
                'long' => $postCode->getLongitude(),
                'lat' => $postCode->getLatitude()
            ];
        }

        return [
            'long' => 0,
            'lat' => 0
        ];
    }

    /**
     * @return \App\CoreBundle\Repository\RestaurantAddressRepository
     */
    private function getRestaurantAddressRepository()
    {
        return $this->container->get('doctrine')
            ->getRepository('AppCoreBundle:RestaurantAddress');
    }

    /**
     * @return \App\CoreBundle\Repository\RestaurantRepository
     */
    private function getRestaurantRepository()
    {
        return $this->container->get('doctrine')
            ->getRepository('AppCoreBundle:Restaurant');
    }

    /**
     * @return \App\CoreBundle\Repository\PostCodeRepository
     */
    private function getPostCodeRepository()
    {
        return $this->container->get('doctrine')
            ->getRepository('AppCoreBundle:PostCode');
    }

    /**
     * @param $longitude
     * @param $latitude
     * @return array
     */
    private function getRestaurantsByLongAndLat($longitude, $latitude)
    {
        return $this->findClosestRestaurants(
            $longitude,
            $latitude,
            3
        );
    }

    /**
     * @param $restaurantId
     * @param $service
     * @param int $days
     * @return array
     */
    public function processGetRestaurantOpeningHoursAction($restaurantId, $service, $days = 6)
    {
        return $this->getOpeningHours($restaurantId, $service, $days);
    }

    /**
     * @param $query
     * @param $long
     * @param $lat
     * @param $id
     * @return array
     */
    public function processGetRestaurantAction($query, $long, $lat, $id)
    {
        return $this->getRestaurantsByParams($query, $long, $lat, $id);
    }


    /**
     * @param $query
     * @param $long
     * @param $lat
     * @param $id
     * @return array
     * @throws BadRequestException
     * @throws NoContentException
     */
    public function getRestaurantsByParams($query, $long, $lat, $id)
    {
        switch (true) {
            case $query:
                $restaurants = $this->getRestaurantsByPostCode($query);
                break;
            case $long && $lat:
                $restaurants = $this->getRestaurantsByLongAndLat($long,$lat);
                break;
            case $id:
                $restaurants = $this->getRestaurantsById($id);
                break;
            default:
                throw new BadRequestException;
        }

        if (!count($restaurants)) {
            throw new NoContentException();
        }

        return $restaurants;
    }

    /**
     * @param $postCode
     * @return array
     */
    private function getRestaurantsByPostCode($postCode)
    {
        $coordinates = $this->findCoordinatesByPostCodeOrAddressOrCity($postCode);

        return $this->findClosestRestaurants(
            $coordinates['long'],
            $coordinates['lat'],
            3
        );
    }

    /**
     * @param $id
     * @return array
     */
    private function getRestaurantsById($id)
    {
        return $this->getRestaurantRepository()
                    ->findBy(
                        ['id'=>$id,'active'=>1,'visible'=>1],
                        null,
                        1
                    );
    }
}
