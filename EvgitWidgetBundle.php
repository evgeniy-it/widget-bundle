<?php

namespace Evgit\Bundle\WidgetBundle;

use Evgit\Bundle\WidgetBundle\DependencyInjection\Compiler\CacheCompilerPass;
use Evgit\Bundle\WidgetBundle\DependencyInjection\Compiler\WidgetCollectionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EvgitNewsBundle
 */
class EvgitWidgetBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new WidgetCollectionCompilerPass());
        $container->addCompilerPass(new CacheCompilerPass());
    }
}
