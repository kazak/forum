<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 11 10 2015
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Handler\ProductHandler;
use App\CoreBundle\Handler\ProductSettingsHandler;
use App\CoreBundle\Model\Controller\EntityController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ProductController
 * @package App\BackOfficeBundle\Controller
 */
class ProductController extends EntityController
{
    /**
     * @var string
     */
    private $role = "ROLE_FROM_SUPER_USER";

    /**
     * @return ProductHandler
     */
    public function getHandler()
    {
        return $this->container->get('app_core.product.handler');
    }

    /**
     * @return ProductSettingsHandler
     */
    public function getSettingsHandler()
    {
        return $this->container->get('app_core.product_settings.handler');
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
     * @Template()
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

    /**
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function settingsListAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $settings = $this->getHandler()->getProductVariantSettingsList();

        return $this->render(
            'AppBackOfficeBundle:Product/Include:settings_list.html.twig',
            ['settings' => $settings]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function settingsDeleteAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $data = $this->getSettingsHandler()->processDeleteAction($request, $id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function settingsUpdateAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $response = $this->getSettingsHandler()->processEditAction($request, $id);
        if ($response['errorCode'] == 200) {
            return $this->render(
                'AppBackOfficeBundle:Product/Include:settings_edit.html.twig',
                ['setting' => $response['data']]
            );
        }
    }

    /**
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function settingsCreateAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $response = $this->getSettingsHandler()->processCreateAction($request);

        if (isset($response['errorCode']) && $response['errorCode'] == 200) {
            return $this->render(
                'AppBackOfficeBundle:Product/Include:settings_edit.html.twig',
                ['setting' => $response['data']]
            );
        }
        return $this->render(
            'AppBackOfficeBundle:Product/Include:settings_create.html.twig',
            ['setting' => null]
        );
    }

    /**
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function optionsListAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $options = $this->getHandler()->getProductOptions();

        return $this->render(
            'AppBackOfficeBundle:Product/Include:options_list.html.twig',
            ['options' => $options]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function optionsDeleteAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $data = $this->getHandler()->processOptionsDeleteAction($request, $id);
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function optionsUpdateAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $response = $this->getHandler()->processOptionsEditAction($request, $id);
        if ($response['errorCode'] == 200) {
            return $this->render(
                'AppBackOfficeBundle:Product/Include:options_edit.html.twig',
                ['option' => $response['data']]
            );
        }

        return $response;
    }

    /**
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function optionsCreateAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $response = $this->getHandler()->processOptionsCreateAction($request);

        if (isset($response['errorCode']) && $response['errorCode'] == 200) {
            return $this->render(
                'AppBackOfficeBundle:Product/Include:options_edit.html.twig',
                ['option' => $response['data']]
            );
        }
        return $this->render(
            'AppBackOfficeBundle:Product/Include:options_create.html.twig',
            ['options' => null]
        );
    }
}
