<?php

namespace Vlabs\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
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
        $treeBuilder = new TreeBuilder('vlabs_media');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_base_url')->isRequired()->end()
                ->arrayNode('resize')
                    ->useAttributeAsKey('mapping')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('queuing')->isRequired()->end()
                            ->scalarNode('filesystem')->isRequired()->end()
                            ->scalarNode('base_url')->isRequired()->end()
                            ->arrayNode('thumbs')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('uri_prefix')->isRequired()->end()
                                        ->arrayNode('size')
                                            ->children()
                                                ->scalarNode('width')->isRequired()->end()
                                                ->scalarNode('height')->isRequired()->end()
                                            ->end()
                                        ->end()
                                        ->scalarNode('mode')->defaultValue('inset')->end()
                                        ->scalarNode('relative_resize')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
