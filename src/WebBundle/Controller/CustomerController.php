<?php
/**
 * Created by forum.
 * User: dss
 * Date: 19.08.16
 * Time: 13:48
 */

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CustomerController
 * @package WebBundle\Controller
 */
class CustomerController extends Controller
{

    /**
     * @return Response
     */
    public function homeEditAction()
    {
        $user = $this->getUser();

        return $this->render('SonataUserBundle:Profile:homeEdit.html.twig',[
            'user' => $user
        ]);
    }

    /**
     * @return Response
     */
    public function myMailAction()
    {
        $user = $this->getUser();

        return $this->render('SonataUserBundle:Profile:homeEdit.html.twig',[
            'user' => $user
        ]);
    }
}