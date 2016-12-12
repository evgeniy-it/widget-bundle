<?php

namespace Evgit\Bundle\WidgetBundle\Widget;

/**
 * class SimpleWidget
 */
class SimpleWidget extends AbstractWidget
{
    /**
     * @param array $options
     *
     * @return string
     */
    public function process(array $options)
    {
        if (!empty($options['tpl'])) {
            $this->setTemplate($options['tpl']);
        }

        return $this->render($options);
    }
}
