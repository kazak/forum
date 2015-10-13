<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 22 05 2015
 */
namespace App\CoreBundle\Model\Controller;

use App\CoreBundle\Handler\RestaurantHandler;
use App\CoreBundle\Service\GeoService\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EntityController.
 */
abstract class EntityController extends Controller implements EntityControllerInterface
{
    protected $locale = 'no';
    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->locale = $request->getLocale();

        return $this->getHandler()->processIndexAction($request);
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function createAction(Request $request, $id = null)
    {
        $this->locale = $request->getLocale();

        if (!$this->checkUser()) {
            return $this->redirectToLogin();
        }

        return $this->getHandler()->processCreateAction($request);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function showAction(Request $request, $id)
    {
        if (!$this->checkUser()) {
            return $this->redirectToLogin();
        }

        $this->locale = $request->getLocale();

        return $this->getHandler()->processShowAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        if (!$this->checkUser()) {
            return $this->redirectToLogin();
        }

        $this->locale = $request->getLocale();

        return $this->getHandler()->processUpdateAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->checkUser()) {
            return $this->redirectToLogin();
        }

        $this->locale = $request->getLocale();

        return $this->getHandler()->processDeleteAction($request, $id);
    }

    /**
     * @return RestaurantHandler
     */
    public function getHandler()
    {
        // TODO: define controller as service and pass handler service id as parameter
        return $this->container->get('app_core.restaurant.handler');
    }

    /**
     * @return bool
     */
    public function checkUser()
    {
        $user = $this->getUser();

        if (null === $user) {
            return false;
        }

        if (true !== $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return false;
        }

        return true;
    }

    /**
     * @return RedirectResponse
     */
    public function redirectToLogin()
    {
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @return bool
     */
    public function permission($roles)
    {
        $user = $this->getUser();

        if (null !== $user) {
            if(is_array($roles)){
                $permissions = $roles;
            }else{
                $permissions = $this->container->getParameter($roles);
            }

            if (true === $this->get('security.authorization_checker')->isGranted($permissions)) {

                return true;
            }
        }
        return false;
    }
}
