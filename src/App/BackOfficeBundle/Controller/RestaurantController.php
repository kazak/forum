<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 14 05 2015
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class RestaurantController
 * @package App\BackOfficeBundle\Controller
 */
class RestaurantController extends EntityController
{
    /**
     * @var string
     */
    private $role = "ROLE_FROM_CC";

    /**
     * @return RedirectResponse|Response
     */
    public function openHoursAction()
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $currentRestaurants = $this->get('app_core.open_hours.handler')->getCurrentRestaurants();

        return $this->render(
            'AppBackOfficeBundle:BackOffice:restaurant_opening_hours.html.twig',
            [
                'restaurants' => $currentRestaurants,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function openHoursIdAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $currentRestaurants = $this->get('app_core.open_hours.handler')->getCurrentRestaurantsByID($id);

        return $this->render(
            'AppBackOfficeBundle:BackOffice:restaurant_opening_hours.html.twig',
            [
                'restaurants' => [$currentRestaurants],
            ]
        );
    }

    /**
     * @param $id
     * @return RedirectResponse|Response
     */
    public function outOfStockIdAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        $settingsMandatory = $this->get('app_core.settings.handler')->getSettingsOOSMandatory($id);
        $settingsOptionaly = $this->get('app_core.settings.handler')->getSettingsOOSOptional($id);
        $restaurant = $this->getHandler()->getEntity($id);
        return $this->render(
            'AppBackOfficeBundle:BackOffice:out_of_stock.html.twig',
            [
                'restaurant' => $restaurant,
                'settings_mandatory' => $settingsMandatory,
                'settings_optionaly' => $settingsOptionaly
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function changeOOSMandatoryAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->get('app_core.settings.handler')->setSettingsOOSMandatory($request, $id);

    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function changeOOSOptionalAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->get('app_core.settings.handler')->setSettingsOOSOptional($request, $id);

    }

    /**
     * @param $id
     * @return RedirectResponse|Response
     */
    public function changeRemoveOptionalAction($id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->get('app_core.settings.handler')->setSettingsRemoveOptional($id);

    }

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function changeOpenHoursAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->getService()->processUpdateAction($request);
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->container->get('app_core.open_hours.handler');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function changeOpenDateAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->getService()->processChangeDateAction($request);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteOpenDateAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }

        return $this->getService()->processDeleteDateAction($request);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function changePerformanceAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $data = $this->container->get('app_core.restaurant.handler')
            ->processChangePerformanceAction($request, $id);

        if ($data instanceof RedirectResponse) {
            return $data;
        }
        return $this->render(
            'AppBackOfficeBundle:Restaurant:performances.html.twig',
            [
                'restaurant' => $data['restaurant'],
                'errors' => $data['errors']
            ]
        );
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
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function disableAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return $this->getHandler()->processDisableAction($request, $id);
    }
}
