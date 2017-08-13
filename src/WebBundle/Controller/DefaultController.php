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
    public function cityAction(Request $request, $url)
    {
        $city = $this->container->get('city.handler')->getEntityBy([ 'slug' => $url ]);
        $organizes =  $this->container->get('organize.handler')->getEntities([ 'city' => $city ]);

        return $this->render('WebBundle:Default:city.html.twig',[
            'city' => $city,
            'organizes' => $organizes
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

    /**
     * @return Response
     */
    public function contactsAction()
    {
        return $this->render('WebBundle:Default:contact.html.twig');
    }

    /**
     * @return Response
     */
    public function aboutAction()
    {
        return $this->render('WebBundle:Default:about.html.twig');
    }
}
