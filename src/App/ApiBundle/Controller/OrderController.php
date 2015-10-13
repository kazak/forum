<?php

/**
 * @author      :     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 18 08 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderController
 * @package App\ApiBundle\Controller
 */
class OrderController extends EntityRESTController
{
    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->container->get('app_core.order.handler');
    }

    /**
     * Save order type.
     *
     * @ApiDoc(
     *   section="Order",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     
     * @Annotations\Post("/ordertype")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Annotations\View()
     */
    public function postOrdertypeAction(Request $request)
    {
        $response = $this->getOpen();

        if(!$response) {
            $response = $this->getService()->processSaveOrdertypeInSession($request);
        }

        $view = $this->view($response, $response['errorCode']);

        return $this->handleView($view);
    }
}
