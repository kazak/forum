<?php

/**
 * @author: aat <aat@norce.digital>
 *
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 09.09.2015
 */
namespace App\CoreBundle\Handler\Helpers;

use App\CoreBundle\Entity\Restaurant;
use App\CoreBundle\Entity\RestaurantOpeningHour;

/**
 * Class RestaurantHelper
 * @package App\CoreBundle\Handler\Helpers
 */
abstract class RestaurantHelper
{

    /**
     * @var array
     */
    private static $serviceMap = [
        'takeaway' => 'take-away',
        'hotel' => 'in-house',
        'house' => 'in-house',
        'inhouse' => 'in-house',
        'delivery' => 'delivery'
    ];

    /**
     * @var array
     */
    private static $dayMap = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday'
    ];

    /**
     * @param $service
     * @return mixed
     */
    private static function getServiceType($service)
    {
        $service = strtolower($service);
        $res = $service;
        if (array_key_exists($service, self::$serviceMap)) {
            $res = self::$serviceMap[$service];
        }
        return $res;
    }

    /**
     * @param $openingHours
     * @param $service
     * @return array
     */
    private static function getOpeningHoursByService($openingHours, $service)
    {
        $hoursByService = [];
        $currentDate = new \DateTime();
        $curentDay = (int)$currentDate->format('N');
        foreach ($openingHours as $openingHour) {
            /**
             * @var RestaurantOpeningHour $openingHour
             * @var RestaurantOpeningHour $openingHoursByService
             */
            if ($openingHour->getService() == $service) {
                if ($openingHour->getDayOfWeek() !== null) {
                    $arrayDayIndex = $openingHour->getDayOfWeek() - $curentDay;
                    if ($arrayDayIndex < 0) {
                        $arrayDayIndex += 7;
                    }
                    if (!isset($hoursByService[$arrayDayIndex])) {
                        $hoursByService[$arrayDayIndex] = $openingHour;
                    }
                } else {
                    for ($i = 0; $i < 6; $i++) {
                        $dayText = ($i === 0) ? 'now' : 'tomorrow';
                        if ($i > 1) {
                            $dayText .= '+' . ($i - 1) . 'day';
                        }
                        $neadlyDate = new \DateTime($dayText);
                        if ($openingHour->getDate() && $neadlyDate->format('Y-m-d') == $openingHour->getDate()->format('Y-m-d')) {
                            $hoursByService[$i] = $openingHour;
                            break;
                        }
                    }
                }
            }
        }
        ksort($hoursByService);
        return $hoursByService;
    }

    /**
     * @param Restaurant $restaurant
     * @param $service
     * @return string
     */
    public static function getOpeningHourString(Restaurant $restaurant, $service)
    {
        $service = self::getServiceType($service);
        $openingHours = $restaurant->getOpenHours();
        $currentDate = new \DateTime();
        $openingHoursByService = self::getOpeningHoursByService($openingHours, $service);
        $res = 'Opening hours';

        if (count($openingHoursByService) === 0) {
            return 'Closed';
        }
        foreach ($openingHoursByService as $kay => $openingHourByService) {
            /**
             * @var RestaurantOpeningHour $openingHourByService
             */
            if (
                $kay === 0 &&
                $openingHourByService->getClosingTime() > $currentDate
            ) {
                $res = $res . ' ' . $openingHourByService->getOpeningTime()->format('G:i')
                    . ' - ' . $openingHourByService->getClosingTime()->format('G:i');
                break;
            } elseif (
                $openingHourByService->getOpeningTime() < $openingHourByService->getClosingTime()
            ) {
                if ($kay === 1) {
                    $res = 'Opens tomorrow '
                        . $openingHourByService->getOpeningTime()->format('G:i') .
                        ' - ' . $openingHourByService->getClosingTime()->format('G:i');
                    break;

                } elseif ($kay > 1) {
                    $res = 'Opens'
                        . ' ' . self::$dayMap[$openingHourByService->getDayOfWeek()]
                        . ' ' . $openingHourByService->getOpeningTime()->format('G:i')
                        . ' - ' . $openingHourByService->getClosingTime()->format('G:i');
                    break;
                }
            }
        }
        return $res === 'Opening hours' ? 'Closed' : $res;
    }
}