<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package WebBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $regions = $this->container->get('region.handler')->getEntities();

        return $this->render('WebBundle:Default:index.html.twig',['regions' => $regions]);
    }
}
