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
        $news = $this->container->get('news.handler')->getEntities(['startPage' => true],[],10);

        return $this->render('WebBundle:Default:index.html.twig',[
            'news' => $news
        ]);
    }

    /**
     * @return Response
     */
    public function photoAction()
    {
        $photo = $this->container->get('photo.handler')->getEntities();

        return $this->render('WebBundle:Default:photo.html.twig',[
            'galerey' => $photo
        ]);
    }

    /**
     * @return Response
     */
    public function videoAction()
    {
        $video = $this->container->get('video.handler')->getEntities();

        return $this->render('WebBundle:Default:video.html.twig',[
            'videos' => $video
        ]);
    }

    public function autoAction(Request $request, $id)
    {
        $transport = $this->container->get('transport.handler')->getEntity($id);

        return $this->render('WebBundle:Default:transport.html.twig',[
            'transport' => $transport
        ]);
    }
}
