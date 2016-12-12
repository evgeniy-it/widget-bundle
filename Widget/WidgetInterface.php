<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

/**
 * interface WidgetInterface
 */
interface WidgetInterface
{
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
