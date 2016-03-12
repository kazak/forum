<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @param Request $request
     * @param $url
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function regionAction(Request $request, $url)
    {
        $region = $this->container->get('region.handler')->getEntityBy(['slug'=>$url]);

        return $this->render('WebBundle:Default:region.html.twig',['region' => $region]);
    }

    /**
     * @param Request $request
     * @param $url
     */
    public function townAction(Request $request, $url)
    {

    }
}
