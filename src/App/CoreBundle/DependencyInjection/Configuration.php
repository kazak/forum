<?php

namespace App\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_core');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('sms')->children()
            ->scalarNode('uri')->end()
            ->scalarNode('userpass')->end()
            ->scalarNode('maxInvalidTried')->end()
            ->scalarNode('maxMessageFromIp')->end()
            ->scalarNode('maxMessageFromPhone')->end()
            ->end()->end()
            ->end();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            // Geo
            ->arrayNode('geo')->children()
                // Geodataonline
                ->arrayNode('geodataonline')->children()
                    ->scalarNode('baseurl')->end()
                    ->scalarNode('tokenurl')->end()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()->end()
                // Arcgis
                ->arrayNode('arcgis')->children()
                    ->scalarNode('baseurl')->end()
                    ->scalarNode('tokenurl')->end()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                    ->scalarNode('referer')->end()
                    ->scalarNode('return_type')->end()
                    ->scalarNode('inSR')->end()
                ->end()->end()
            // End geo
            ->end()->end()
        // End root
        ->end()->end();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('sitemap')->children()
                    ->arrayNode('priority')->children()
                        ->scalarNode('dolly_homepage')->end()
                        ->scalarNode('dolly_restaurant_index')->end()
                        ->scalarNode('dolly_shop_index')->end()
                        ->scalarNode('dolly_shop_products_index')->end()
                        ->scalarNode('dolly_menu_page')->end()
                        ->scalarNode('dolly_restaurant_show')->end()
                        ->end()->end()
                // End sitemap
                ->end()->end()
            // End root
            ->end()->end();

        $rootNode
            ->addDefaultsIfNotSet()->children()
                ->arrayNode('uploading')
                    ->children()
                        ->arrayNode('allowed_file_types')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('allowed_file_types_image')
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('upload_dir')->end()
                ->end()->end()
            // End root
            ->end()->end();

        $rootNode
            ->addDefaultsIfNotSet()->children()
                ->arrayNode('classes_identifier')
                    ->prototype('scalar')->end()
            ->end()
            // End root
            ->end()->end();
        $rootNode
            ->addDefaultsIfNotSet()->children()
            ->arrayNode('restaurant_default_values')
            ->prototype('scalar')->end()
            ->end()
            // End root
            ->end()->end();
        $rootNode
            ->addDefaultsIfNotSet()->children()
            ->arrayNode('max_orders_to_one_person')
            ->prototype('scalar')->end()
            ->end()
            // End root
            ->end()->end();

        $rootNode
        ->addDefaultsIfNotSet()
        ->children()
        ->scalarNode('translations_enabled')->end()
        ->end();

        return $treeBuilder;
    }
}
