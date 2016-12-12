<?php

namespace Evgit\Bundle\WidgetBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * class ProductExtension
 */
class WidgetExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

     /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('widget', [$this, 'processWidget'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function processWidget($content)
    {
        return $this->container->get("evgit.widget.parser")->process($content);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return "widget";
    }

    /**
     * @param ContainerInterface $container
     *
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }
}
