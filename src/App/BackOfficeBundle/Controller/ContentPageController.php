<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 04 06 2015
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ContentPageController
 * @package App\BackOfficeBundle\Controller
 */
class ContentPageController extends EntityController
{
    /**
     * @var string
     */
    private $role = 'ROLE_FROM_MARKET';

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->container->get('app_core.content_page.handler');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::indexAction($request);
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function createAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::createAction($request);
    }

    /**
     * @Template("AppDollyBundle:ContentPage:show.html.twig")
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function showAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::showAction($request, $id);
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
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::updateAction($request, $id);
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
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::deleteAction($request, $id);

    }

}
