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

class CustomerController extends Controller
{

    public function homeEditAction()
    {
        $user = $this->getUser();

        return $this->render('WebBundle:Customer:homeEdit.html.twig',[
            'user' => $user
        ]);
    }
}