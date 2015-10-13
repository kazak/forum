<?php

/**
 * @author:     lars <lars@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 06 07 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use App\ApiBundle\Model\Controller\ResponseBuilderHandlerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use App\CoreBundle\Entity\RestaurantOpeningHour;

/**
 * Class RestaurantController.
 */
class RestaurantController extends EntityRESTController implements ResponseBuilderHandlerInterface
{
    /**
     * @ApiDoc(
     *   section="Restaurant",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Bad request",
     *     404 = "No content"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="query", default=null, strict=true, nullable=true, description="query")
     * @Annotations\QueryParam(name="long", default=null, strict=true, nullable=true, description="Longitude")
     * @Annotations\QueryParam(name="lat", default=null, strict=true, nullable=true, description="Latitude")
     * @Annotations\QueryParam(name="id", default=null, strict=true, nullable=true, description="id", requirements="\d+")
     * @Annotations\Get("/restaurants")
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @return array
     */
    public function getRestaurantAction(ParamFetcher $paramFetcher, Request $request)
    {
        return $this->process(
            ['query', 'long', 'lat', 'id'], 'buildGetRestaurants', 'getRestaurantsByParams'
        );
    }

    /**
     * @ApiDoc(
     *   section="Restaurant",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     204 = "No restaurants found",
     *     400 = "Returned when the query was not valid"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="restaurant_id", default=null, strict=true, nullable=false, description="Restaurant ID", requirements="\d+")
     * @Annotations\QueryParam(name="service", default="take-away", strict=true, nullable=false, description="Service")
     * @Annotations\QueryParam(name="days", default="6", strict=true, nullable=false, description="Nearest days", requirements="\d+")
     * @Annotations\Get("/restaurants/opening_hours")
     *
     * @param ParamFetcher $fetcher
     * @return array
     */
    public function getRestaurantOpeningHoursAction(ParamFetcher $fetcher)
    {
        return [
            'data' => $this->getHandler()
                           ->getOpeningHours($fetcher->get('restaurant_id'), $fetcher->get('service'), $fetcher->get('days'))
        ];
    }

    /**
     * @return \App\CoreBundle\Handler\RestaurantHandler
     */
    private function getHandler()
    {
        return $this->container->get('app_core.restaurant.handler');
    }

    /**
     * @inheritDoc
     */
    public function getResponseBuilderServiceName()
    {
        return 'app_api.restaurant_response_builder.service';
    }

    /**
     * @inheritDoc
     */
    public function getHandlerServiceName()
    {
        return 'app_core.restaurant.handler';
    }
}
