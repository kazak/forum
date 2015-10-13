<?php

namespace App\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @param $name
     *
     * @return Response
     */
    public function indexAction($name)
    {
        return $this->render('AppCoreBundle:Default:index.html.twig', ['name' => $name]);
    }
}
