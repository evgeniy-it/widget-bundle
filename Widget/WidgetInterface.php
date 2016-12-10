<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

/**
 * interface WidgetInterface
 */
interface WidgetInterface
{
    /**
     * @param string $template
     *
     * @return WidgetInterface
     */
    public function setTemplate($template);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $options
     *
     * @return string
     */
    public function process(array $options);
}
