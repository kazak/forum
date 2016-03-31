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
        $partners = $this->container->get('partner.handler')->getEntities(['visible' => true],[],12);

        return $this->render('WebBundle:Default:index.html.twig',[
            'regions' => $regions,
            'news' => $news,
            'partners' => $partners
        ]);
    }

    /**
     * @param Request $request
     * @param $url
     * @return Response
     */
    public function regionAction(Request $request, $url)
    {
        $region = $this->container->get('region.handler')->getEntityBy(['slug'=>$url]);
        $cityes = $this->container->get('city.handler')->getEntities(['region'=>$region]);

        return $this->render('WebBundle:Default:region.html.twig',[
            'region' => $region,
            'cityes' => $cityes
        ]);
    }

    /**
     * @param Request $request
     * @param $url
     * @return Response
     */
    public function cityAction(Request $request, $url)
    {
        $city = $this->container->get('city.handler')->getEntityBy(['slug'=>$url]);

        return $this->render('WebBundle:Default:city.html.twig',[
            'city' => $city
        ]);
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
