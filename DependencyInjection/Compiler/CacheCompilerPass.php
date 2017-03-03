<?php

namespace Evgit\Bundle\WidgetBundle\DependencyInjection\Compiler;

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

        $parser = $container->getDefinition('evgit.widget.parser');

        $parser->addMethodCall("setCacheProvider", [new Reference('evgit.cache')]);
    }
}
