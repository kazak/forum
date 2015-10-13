<?php

/**
 * @author      :     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 14 07 2015
 */
namespace App\CoreBundle\Handler;

use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class MenuHandler.
 */
class SitemapHandler
{
    use ContainerAwareTrait;

    /**
     * @var bool|\Doctrine\Common\Persistence\ObjectManager|object
     */
    private $doctrineManager = false;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @param Container $container
     * @param $config
     */
    public function __construct(Container $container, $config)
    {
        $this->setContainer($container);
        $this->config = $config;
        $this->doctrineManager = $this->container->get('doctrine')->getManager();
    }

    /**
     * @return array
     */
    public function getSitemapFiles()
    {
        $dir = $this->container->get('kernel')->getRootDir().'/../web/sitemap';
        $fileSystem = new Filesystem();
        $files = [];

        if ($fileSystem->exists($dir)) {
            $finder = new Finder();
            $finder->files()->in($dir);

            foreach ($finder as $key => $file) {
                $files[$key]['name'] = $file->getRelativePathname();

                if (file_exists($file->getRealpath())) {
                    $files[$key]['updated'] = date('m d Y H:i:s.', filectime($file->getRealpath()));
                }
            }
        }

        return $files;
    }

    /**
     * add Listeners
     */
    public function addListeners()
    {
        //add homepage
        $this->addListener('dolly_homepage');

        //add restaurant
        $this->addListener('dolly_restaurant_index');

        //add shop
        $this->addListener('dolly_shop_index');

        //add shop products
        $this->addListener('dolly_shop_products_index');

        //add content page
        foreach ($this->doctrineManager->getRepository('AppCoreBundle:ContentPage')->findBy(['siteMap' => 1]) as $contPage) {
                $this->addListener('dolly_contentpage', ['id' => $contPage->getSlug()]);
        }

        //Add menu urls
        foreach ($this->doctrineManager->getRepository('AppCoreBundle:Menu')->findBy(['hideForSearchEngines' => '0','siteMap' => 1 ]) as $menu) {
            $this->addListener('dolly_menu_page', ['id' => $menu->getSlug()]);
        }

        //Add restaurant urls
        foreach ($this->doctrineManager->getRepository('AppCoreBundle:Restaurant')->findAll() as $restaurant) {
            $this->addListener('dolly_restaurant_show', ['id' => $restaurant->getSlug()]);
        }
    }

    /**
     * @param string     $routerAlias
     * @param array|null $routerParams
     */
    private function addListener($routerAlias, array $routerParams = null)
    {
        $event = $this->container->get('event_dispatcher');
        $router = $this->container->get('router');

        $event->addListener(SitemapPopulateEvent::ON_SITEMAP_POPULATE,
            function (SitemapPopulateEvent $event) use ($router, $routerAlias, $routerParams) {
                $url = $router->generate($routerAlias, $routerParams, true);
                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        new \DateTime(),
                        UrlConcrete::CHANGEFREQ_HOURLY,
                        $this->config['priority'][$routerAlias]
                    ),
                    'default'
                );
            });
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
