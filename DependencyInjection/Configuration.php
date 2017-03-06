<?php

namespace Evgit\Bundle\WidgetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * class Configurarion
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('evgit_widget');

        $rootNode->children()
                ->booleanNode('cacheable')
                    ->defaultFalse()
                ->end()
                ->scalarNode("cache_provider")
                    ->defaultValue("redis")
                ->end()
                ->integerNode("cache_ttl")
                    ->defaultValue(86400)
                ->end()
            ->end();

        return  $treeBuilder;
    }
}
