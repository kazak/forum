<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 22 06 2015
 */
namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use App\CoreBundle\Handler\CustomerHandler;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CustomerController
 * @package App\ApiBundle\Controller
 */
class CustomerController extends EntityRESTController
{
    /**
     * @return CustomerHandler
     */
    public function getHandler()
    {
        return $this->container->get('app_core.usere.handler');
    }

    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\Get("/customer")
     *
     * @Annotations\View()
     */
    public function getUserCustomerAction()
    {
        return $this->process(
            [], 'buildGetUserCustomerResponse', 'processGetUserAction', false
        );
    }

    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\Post("/customer")
     *
     * @Annotations\View()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUserCustomerAction(Request $request)
    {
        $response = $this->getOpen();

        if(!$response) {
            $response = $this->getHandler()->processRegistrationAction($request);

            if ($response['facebook']) {
                $response = $this->getHandler()->AuthFacebook($request);
            }

            if ($response['errorCode'] == 200) {
                $this->sessionStart();
                $this->get('fos_user.security.login_manager')->loginUser('fos_user.firewall_name', $response['data']);
            }
        }

        $view = $this->view($response, $response['errorCode'])
            ->setTemplate('AppApiBundle:Customer:customer.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\Post()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Annotations\View()
     */
    public function postAction(Request $request)
    {
        $response = $this->getOpen();

        if(!$response){
            $response = $this->getHandler()->processRegistrationAction($request);

            if ($response['errorCode'] == 200) {
                $this->sessionStart();
                $this->get('fos_user.security.login_manager')->loginUser('fos_user.firewall_name', $response['data']);
            }
        }

        $view = $this->view($response, $response['errorCode'])
            ->setTemplate('AppApiBundle:Customer:customer.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\Post("/reset-password")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Annotations\View()
     */
    public function postUserResetPasswordAction(Request $request)
    {
        $response = $this->getHandler()->processResetPasswordAction($request);

        if ($response['errorCode'] == 200) {
            $this->sessionStart();
            $this->get('fos_user.security.login_manager')->loginUser('fos_user.firewall_name', $response['data']);
        }
        $view = $this->view($response, $response['errorCode'])
            ->setTemplate('AppApiBundle:Customer:customer.html.twig');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Post("/login-facebook")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Annotations\View()
     */
    public function postUserFaceBookLoginAction(Request $request)
    {
        $response = $this->getOpen();

        if(!$response) {
            $response = $this->getHandler()->AuthFacebook($request);

            if (!$response['error']) {
                $this->sessionStart();
                $this->get('fos_user.security.login_manager')->loginUser('fos_user.firewall_name', $response['data']);
            }
        }

        $view = $this->view($response, $response['errorCode'])
            ->setTemplate('AppApiBundle:Customer:customer.html.twig');

        return $this->handleView($view);

    }

    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Post("/login")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Annotations\QueryParam(name="phone", default=null, strict=true, nullable=false, description="Phone")
     * @Annotations\QueryParam(name="password", default=null, strict=true, nullable=false, description="Password")
     * @Annotations\View()
     */
    public function postUserLoginAction(Request $request)
    {
        $response = $this->getOpen();

        if(!$response) {
            $encoder = $this->get('security.encoder_factory');

            $response = $this->getHandler()->Auth($request, $encoder);

            if (!$response['error']) {
                $this->sessionStart();
                $this->get('fos_user.security.login_manager')->loginUser('fos_user.firewall_name', $response['data']);
            }
        }

        $view = $this->view($response, $response['errorCode'])
            ->setTemplate('AppApiBundle:Customer:customer.html.twig');

        return $this->handleView($view);
    }

    /**
     * start session
     */
    private function sessionStart(){
        $session = $this->container->get('session');
        if(!$session->isStarted()){
            $session->start();
        }
    }

    /**
     * @inheritDoc
     */
    public function getResponseBuilderServiceName()
    {
        return 'app_api.customer_response_builder.service';
    }

    /**
     * @inheritDoc
     */
    public function getHandlerServiceName()
    {
        return 'app_core.customer.handler';
    }
}
