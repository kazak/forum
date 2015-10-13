<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 25.09.15
 */

namespace App\ApiBundle\Service;
use App\ApiBundle\Model\Service\BaseResponseBuilder;
use App\CoreBundle\Entity\Restaurant;
use App\CoreBundle\Exception\Restaurant\NoContentException;
use App\CoreBundle\Handler\Helpers\RestaurantHelper;

/**
 * Class RestaurantResponseBuilder
 * @package App\ApiBundle\Service
 */
class RestaurantResponseBuilder extends BaseResponseBuilder
{
    /**
     * @param Restaurant[] $restaurants
     * @return array
     * @throws NoContentException
     */
    public function buildGetRestaurants($restaurants)
    {
        return [
            'status' => 200,
            'data' => array_map(
                function(Restaurant $restaurant)
                {
                    return [
                        'id' => $restaurant->getId(),
                        'name' => $restaurant->getTitle(),
                        'address' => $restaurant->getAddress()->getAddress(),
                        'postCode' => $restaurant->getAddress()->getPostCode(),
                        'postOffice' => $restaurant->getAddress()->getPostOffice(),
                        'isOpen' => true,
                        'openingHourString' => RestaurantHelper::getOpeningHourString($restaurant, 'TAKEaway'),
                    ];
                },
                $restaurants
            )
        ];
    }
}