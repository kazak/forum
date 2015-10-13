<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 06 07 2015
 */
namespace App\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;

/**
 * Class SmsController.
 */
class SmsController extends FOSRestController
{
    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->container->get('app_core_sms');
    }

    /**
     * @ApiDoc(
     *   section="Phone",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Put("/pin")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putPhonePinAction(Request $request)
    {
        $response = [];
        $post = $request->request->all();

        if (!isset($post['id'], $post['pin'], $post['phone'])) {
            $response['error'] = true;
            $response['errorCode'] = 400;
            $response['errorMessage'] = 'Bad request';
        } else {
            $response = $this->getService()->checkSMSCode($post['id'], $post['pin'], $post['phone']);
        }

        $view = $this->view($response, $response['errorCode']);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section="Phone",
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when successful"
     *   }
     * )
     *
     * @Post("/pin")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postPhonePinAction(Request $request)
    {
        $response = [];
        $post = $request->request->all();
        $clientIp = $request->getClientIp();

        if (!isset($post['phone'])) {
            $response['error'] = true;
            $response['errorCode'] = 400;
            $response['errorMessage'] = 'Bad request';
        } else {
            $response = $this->getService()->sendSMS($post['phone'], $clientIp, $post['check']);
        }

        $view = $this->view($response, $response['errorCode']);

        return $this->handleView($view);
    }
}
