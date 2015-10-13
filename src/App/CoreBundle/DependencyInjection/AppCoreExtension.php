<?php

namespace App\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AppCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('app_sms_send', $config['sms']);
        $container->setParameter('app_core.geo', $config['geo']);
        $container->setParameter('app_core.settings', $config['uploading']);
        $container->setParameter('app_core.sitemap', $config['sitemap']);
        $container->setParameter('app_core.classes_identifier', $config['classes_identifier']);
        $container->setParameter('app_core.translations', $config['translations_enabled']);
        $container->setParameter('app_core.restaurant_default_values', $config['restaurant_default_values']);
        $container->setParameter('app_core.max_orders_to_one_person', $config['max_orders_to_one_person']);
    }
}
