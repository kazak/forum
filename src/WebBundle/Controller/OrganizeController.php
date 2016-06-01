<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 01.06.16
 * Time: 13:23
 */

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class OrganizeController
 * @package WebBundle\Controller
 */
class OrganizeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug)
    {
        $organize = $this->container->get('organize.handler')->getEntityBy(['slug' => $slug]);

        return $this->render('WebBundle:Organize:index.html.twig',[
        'organize' => $organize
        ]);
    }
}