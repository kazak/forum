<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package WebBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $regions = $this->container->get('region.handler')->getEntities();
        $news = $this->container->get('news.handler')->getEntities(['startPage' => true],[],10);

        return $this->render('WebBundle:Default:index.html.twig',['regions' => $regions, 'news' => $news]);
    }

    /**
     * @param Request $request
     * @param $url
     * @return Response
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

    /**
     * @return Response
     */
    public function newsAction()
    {
        $news = $this->container->get('news.handler')->getEntities();

        return $this->render('WebBundle:Default:news.html.twig',['news' => $news]);
    }
}
