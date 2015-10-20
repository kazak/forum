<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 20.10.15
 * Time: 13:13
 */

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebController
 * @package WebBundle\Controller
 */
class WebController extends Controller
{

    /**
     * @Template("WebBundle::menu.html.twig")
     * @param Request $request
     *
     * @return array
     */
    public function headerNavAction(Request $request)
    {
        //$menuHandler = $this->container->get('app_core.pages.handler');
        //$pageList = $menuHandler->getEntityes();
        $pageList =[];

        return [
            'psges' => $pageList,
            'user' => $this->getUser()
        ];
    }
}