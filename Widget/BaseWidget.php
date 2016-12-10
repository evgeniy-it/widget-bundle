<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

/**
 * class BaseWidget
 */
class BaseWidget extends AbstractWidget
{
    /**
     * @param array $options
     *
     * @return string
     */
    public function process(array $options)
    {
        return $this->render($options);
    }
}
