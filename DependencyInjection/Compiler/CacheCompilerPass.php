<?php

namespace Evgit\Bundle\WidgetBundle\DependencyInjection\Compiler;

use Evgit\Bundle\WidgetBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * class CacheCompilerPass
 */
class CacheCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('evgit.widget.parser') || !$container->has('evgit.cache')) {
            return;
        }

        $configs = $container->getExtensionConfig('evgit_widget');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $parser = $container->getDefinition('evgit.widget.parser');
        $parser->addMethodCall("setCacheProvider", [new Reference('evgit.cache')]);
        $parser->addMethodCall("setCacheDefaultTtl", [$config["cache_ttl"]]);
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param array                  $configs
     *
     * @return array
     */
    private function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
