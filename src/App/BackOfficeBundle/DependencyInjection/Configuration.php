<?php

namespace App\BackOfficeBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('app_back_office');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('appearance')->children()
                    ->arrayNode('ckeditor')->children()
                        ->arrayNode('toolbar')
                            // following breaks on "/" element of array, which is responsible for toolbar lines separation
                            /*
                            ->prototype('array')->children()
                                ->scalarNode('name')->isRequired()->end()
                                ->arrayNode('items')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()->end()
                            */
                            ->prototype('variable')->end()
                        ->end()
                    ->end()->end()
                ->end()->end()
            ->end();

        return $treeBuilder;
    }
}
