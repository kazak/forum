<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 04 06 2015
 */
namespace App\DollyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function disabledStatusAction()
    {
        $service = $this->get('app_core.web_state.service');
        $message = $service->getMessage();

        return $this->render('AppCoreBundle:Restaurant:disabled.html.twig', compact('message'));
    }
}
