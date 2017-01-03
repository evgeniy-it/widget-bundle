<?php

namespace Evgit\Bundle\WidgetBundle\DependencyInjection\Compiler;

use Evgit\Bundle\WidgetBundle\Widget\RepositoryWidget;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * class WidgetCollectionCompilerPass
 */
class WidgetCollectionCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('doctrine')) {
            $this->addRepositoryService($container);
        }

        $this->addTaggedServices($container);
    }

    private function addRepositoryService(ContainerBuilder $container)
    {
        $definition = new DefinitionDecorator('evgit.widget.abstract_widget');
        $definition->setClass(RepositoryWidget::class);

        $definition->addMethodCall('setName', ['repository']);
        $definition->addMethodCall('setDoctrine', [new Reference('doctrine')]);
        $definition->addTag("widget");

        $container->setDefinition("evgit.widget.repository_widget", $definition);

        return;
    }

    private function addTaggedServices(ContainerBuilder $container)
    {
        if (!$container->has('evgit.widget.widget_collection')) {
            return;
        }
        $widgets = $container->findTaggedServiceIds("widget");
        $widgetCollectionRenderer = $container->findDefinition("evgit.widget.widget_collection");

        foreach ($widgets as $widget => $tags) {
            $widgetCollectionRenderer->addMethodCall("addWidget", [new Reference($widget)]);
        }
    }
}
