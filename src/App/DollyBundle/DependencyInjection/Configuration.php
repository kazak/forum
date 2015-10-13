<?php

namespace App\DollyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $rootNode = $treeBuilder->root('app_dolly');

        /** @var ArrayNodeDefinition $resources */
        $resources =
            $rootNode
                ->children()
                    ->arrayNode('resources')
                        ->addDefaultsIfNotSet();

        $default = '%kernel.root_dir%/../src/App/DollyBundle/Resources';
        $resources
            ->children()
                ->scalarNode('path')
                    ->treatNullLike($default)
                    ->defaultValue($default)
                ->end();
        $resources->end();

        $default = '%kernel.root_dir%/../web/fonts';
        $rootNode
            ->children()
                ->scalarNode('fonts_dir')
                    ->treatNullLike($default)
                    ->defaultValue($default)
                ->end();

        /** @var ArrayNodeDefinition $contactFormEmail */
        $contactFormEmail = $rootNode
            ->children()
                ->arrayNode('contact_form')
                    ->children()
                        ->arrayNode('email');

        $contactFormEmail
            ->children()
                ->scalarNode('from')
                    ->end();

        $contactFormEmail
            ->children()
                ->scalarNode('to')
                    ->end();

        $contactFormEmail
            ->children()
                ->variableNode('bcc')
                    ->end();

        $contactFormEmail->end();

        return $treeBuilder;
    }
}
