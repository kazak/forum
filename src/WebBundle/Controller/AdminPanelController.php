<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 07.03.16
 * Time: 15:10
 */

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminPanelController
 * @package WebBundle\Controller
 */
class AdminPanelController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($organize_url)
    {
        return $this->render('WebBundle:Default:index.html.twig');
    }
}