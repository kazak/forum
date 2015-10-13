<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 17 06 2015
 */
namespace App\ApiBundle\Controller;

use App\CoreBundle\Exception\InvalidFormException;
use App\CoreBundle\Form\RestaurantOpeningHourType;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class OpenHoursController
 * @package App\ApiBundle\Controller
 */
class OpenHoursController extends FOSRestController
{
    /**
     * Presents the form to use to create a new page.
     *
     * @ApiDoc(
     *   section="Open Hours",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newOpenAction()
    {
        return $this->createForm(new RestaurantOpeningHourType());
    }

    /**
     * @ApiDoc(
     *   section="Open Hours",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @Annotations\View(templateVar="hour")
     *
     * @param int $id the hour id
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getOpenAction($id)
    {
        $hour = $this->getOr404($id);

        return $hour;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->container->get('app_core.open_hours.handler');
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($hour = $this->getService()->findHoliday($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }

        return $hour;
    }

    /**
     * @ApiDoc(
     *   section="Open Hours",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     * templateVar = "form"
     *)
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return FormTypeInterface|View
     */
    public function postHolidayAction(Request $request)
    {
        try {
            return ['id' => $this->getService()->post($request)];
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }
}
