<?php

/**
 * @author:     marius <marius@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 17 06 2015
 */
namespace App\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GeoController
 * @package App\ApiBundle\Controller
 */
class GeoController extends FOSRestController
{
    /**
     * Search for address.
     *
     * @ApiDoc(
     *   section="Geo",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     402 = "Returned when bad request",
     *   }
     * )
     *
     * @QueryParam(name="query", default=null, strict=true, nullable=false, description="Address to search")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */
    public function getSearchAction(ParamFetcher $paramFetcher)
    {
        $query = $paramFetcher->get('query');

        try {
            $searchResult = $this->getService()->search($query);
        } catch (\Exception $e) {
            $searchResult = [];
        }

        $response = [];
        $error = false;
        $errorCode = 200;
        $errorMessage = null;
        $response['data'] = $searchResult;

        $response['error'] = $error;
        $response['errorCode'] = $errorCode;
        $response['errorMessage'] = $errorMessage;

        $view = $this->view($response, $errorCode);

        return $this->handleView($view);
    }

    /**
     * Find a zone.
     *
     * @ApiDoc(
     *   section="Geo",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     402 = "Returned when bad request",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @QueryParam(name="x", default=null, strict=true, nullable=false, description="X coord")
     * @QueryParam(name="y", default=null, strict=true, nullable=false, description="Y coord")
     
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */
    public function getZoneAction(ParamFetcher $paramFetcher)
    {
        $longitude = $paramFetcher->get('x');
        $latitude = $paramFetcher->get('y');

        // findZone throws exception when not found
        try {
            $zone = $this->getService()->findZone($longitude, $latitude);
        } catch (\Exception $e) {
            $zone = null;
        }

        $response = [];

        if (isset($zone[0])) {
            $error = false;
            $errorCode = 200;
            $errorMessage = null;
            $response['data'] = $zone[0];
        } else {
            $error = true;
            $errorCode = 404;
            $errorMessage = 'Zone not found';
            $response['data'] = null;
        }

        $response['error'] = $error;
        $response['errorCode'] = $errorCode;
        $response['errorMessage'] = $errorMessage;

        $view = $this->view($response, $errorCode);

        return $this->handleView($view);
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->container->get('app_core.geo.service');
    }
}
