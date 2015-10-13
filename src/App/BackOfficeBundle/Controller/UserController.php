<?php

namespace App\BackOfficeBundle\Controller;

use FOS\UserBundle\Controller\SecurityController;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package App\BackOfficeBundle\Controller
 */
class UserController extends SecurityController
{
    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        return $this->render('AppBackOfficeBundle:User:login.html.twig', $data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function loginOSAction(Request $request)
    {
        $userHandler = $this->container->get('app_core.customer.handler');
        $response = $userHandler->processAuthFromOS($request);

        if ($response['errorCode'] == 200) {
            return $this->redirectToRoute('app_back_office_start_page');
        }
        return $this->render(
            'AppBackOfficeBundle:User:loginOS.html.twig',
            [
                'last_email' => $response['data']['email'],
                'error' => $response['errorMessage']
            ]
        );
    }


}
