<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 24 07 2015
 */
namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SitemapController
 * @package App\BackOfficeBundle\Controller
 */
class SitemapController extends EntityController
{
    /**
     * @var string
     */
    private $role = 'ROLE_FROM_ADMIN';

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->container->get('app_core.sitemap.handler');
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
            return $this->redirectToRoute('fos_user_security_login');
        }

        $files = $this->getHandler()->getSitemapFiles();

        return $this->render(
            'AppBackOfficeBundle:Sitemap:index.html.twig',
            ['files' => $files]
        );
    }

    /**
     * @return RedirectResponse
     */
    public function generateAction()
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $host = $this->container->getParameter('sitemap.url.host');
        $this->container->get('app_core.sitemap.handler')->addListeners();
        $names = $this->container
            ->get('presta_sitemap.dumper')
            ->dump('sitemap/',
                $host,
                'default',
                ['target' => 'sitemap/']
            );

        if ($names) {
            return $this->redirectToRoute('app_back_office_sitemap_index');
        }

        return $this->redirectToRoute('app_back_office_start_page');
    }


}
