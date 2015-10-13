<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 08 06 2015
 */
namespace App\DollyBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContentPageController.
 */
class ContentPageController extends EntityController
{
    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->container->get('app_core.content_page.handler');
    }

    /**
     * @Template("AppDollyBundle:ContentPage:show.html.twig")
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function showBySlugAction(Request $request, $id)
    {
        return $this->getHandler()->processShowBySlugAction($request, $id);
    }
}
