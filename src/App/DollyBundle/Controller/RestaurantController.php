<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 24 06 2015
 */
namespace App\DollyBundle\Controller;

use App\CoreBundle\Entity\Restaurant;
use App\CoreBundle\Entity\RestaurantOpeningHour;
use App\CoreBundle\Model\Controller\EntityController;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\IdentityTranslator;

/**
 * Class RestaurantController
 * @package App\DollyBundle\Controller
 */
class RestaurantController extends EntityController
{
    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->container->get('app_core.restaurant.handler');
    }

    /**
     * @return mixed
     */
    public function getPageHandler()
    {
        return $this->container->get('app_core.restaurants_page.handler');
    }

    /**
     * @return mixed
     */
    public function getOpenHoursHandler()
    {
        return $this->container->get('app_core.open_hours.handler');
    }

    /**
     * @return mixed
     */
    public function getTranslationHandler()
    {
        return $this->container->get('translator');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->locale = $request->getLocale();
        $post = $request->request->all();
        $search = isset($post['search']) ? $post['search'] : '';

        $restaurants = $this->getHandler()->getEntities([
            'active' => 1,
            'visible' => 1,
        ], [], null);

        return [
            'page' => $this->getPageHandler()->getRepository()->findOneBy([]),
            'entities' => $restaurants,
            'search' => $search,
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param mixed $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function showAction(Request $request, $id)
    {
        $this->locale = $request->getLocale();

        try {
            /**
             * @var Restaurant $restaurant
             */
            $restaurant = $this->getHandler()->getRepository()->findOneBy(['slug' => $id]);
            if (!$restaurant) {
                $restaurant = $this->getHandler()->getEntity($id);
            }
        } catch (EntityNotFoundException $e) {
            return $this->redirectToRoute('dolly_restaurant_index');
        }

        if ($restaurant) {
            $restaurant->setDescriptionTitle($this->locale);
            $openHours = $this->getRestaurantOpenHoursFormatted($restaurant);

            return [
                'page' => $this->getPageHandler()->getRepository()->findOneBy([]),
                'entity' => $restaurant,
                'openhours' => $openHours,
            ];
        }

        return $this->redirectToRoute('dolly_restaurant_index');
    }

    /**
     * @param Restaurant $restaurant
     *
     * @return array
     */
    protected function getRestaurantOpenHoursFormatted($restaurant)
    {
        $openHoursHandler = $this->getOpenHoursHandler();
        $services = $openHoursHandler->getServices();
        $openHours = [];
        $trans = $this->getTranslationHandler();

        foreach ($services as $service) {
            $oh = $openHoursHandler->getRepository()->findBy([
                'restaurant' => $restaurant->getId(),
                'service' => $service,
            ]);
            if (empty($oh)) {
                continue;
            }
            $openHours[$trans->trans('order.services.' . $service, [], 'dolly')] =
                $this->formatOpenHours($oh);
        }

        return $openHours;
    }

    /**
     * @param $openHours
     * @return array
     */
    protected function formatOpenHours($openHours)
    {
        $ohFormatted = [];
        $current = 0;
        $trans = $this->getTranslationHandler();
        $days = $this->formatDay();

        /*
         * @var RestaurantOpeningHour $data
         */
        foreach ($openHours as $data) {
            $maxDate = new \DateTime('tomorrow+13day');
            $curentDate = new \DateTime();
            $time = ($data->getOpeningTime() >= $data->getClosingTime())
                ? $trans->trans('Closed', [], 'dolly')
                : $data->getOpeningTime()->format('H:i') . ' - ' . $data->getClosingTime()->format('H:i');

            if (!is_null($data->getDayOfWeek())) {
                $dayOfWeek = $data->getDayOfWeek();
                $day = $days[--$dayOfWeek];

                if (isset($ohFormatted[$current - 1]) && $time === $ohFormatted[$current - 1]['time']) {
                    $ohFormatted[$current - 1]['days'][] = $day;
                } else {
                    $ohFormatted[] = [
                        'days' => [$day],
                        'time' => $time,
                    ];
                    ++$current;
                }
            } else {
                $dataDate = $data->getDate() ? $data->getDate()->format('Y-m-d') : null;
                if (
                    $curentDate->format('Y-m-d') <= $dataDate &&
                    $maxDate->format('Y-m-d') >= $dataDate
                ) {
                    $day = $data->getReason() ? $data->getReason() . ':' :
                        $trans->trans($data->getDate()->format('d'), [], 'dolly') . '. ' .
                        $trans->trans($data->getDate()->format('F'), [], 'dolly') . ':';
                    $ohFormatted[$day] = [
                        'days' => [$day],
                        'time' => $time,
                    ];
                }
            }
        }

        $ohFormatted = array_map(function ($data) {

            $data['days'] =
                count($data['days']) == 1
                    ? $data['days'][0]
                    : $data['days'][0] . ' - ' . array_pop($data['days']);

            return $data;

        }, $ohFormatted);

        return $ohFormatted;
    }

    /**
     * @return mixed
     */
    protected function formatDay()
    {
        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

        $trans = $this->getTranslationHandler();

        foreach ($days as $i => $day) {
            /**
             * @var IdentityTranslator $trans
             */
            $days[$i] = $trans->trans('day.' . $day, [], 'dolly');
        }

        return $days;
    }
}
