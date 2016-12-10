<?php

namespace Evgit\Bundle\WidgetBundle\Service;

use Evgit\Bundle\WidgetBundle\Widget\WidgetInterface;

/**
 * class WidgetCollection
 */
class WidgetCollection
{
    /**
     * @var array
     */
    private $widgets = [];

    /**
     * @param string $widget
     *
     * @return bool|WidgetInterface
     */
    public function getWidget($widget)
    {
        if (!$this->hasWidget($widget)) {
            return false;
        }

        return $this->widgets[$widget];
    }

    /**
     * @param string $widget
     *
     * @return bool
     */
    public function hasWidget($widget)
    {
        return array_key_exists($widget, $this->widgets);
    }

    /**
     * @param WidgetInterface $widget
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addWidget(WidgetInterface $widget)
    {
        if (empty($widget->getName())) {
            throw new \Exception("Widget can't have blank name");
        }

        if ($this->hasWidget($widget->getName())) {
            throw new \Exception(sprintf("Widget with name \"%s\" has already registered", $widget->getName()));
        }
        $this->widgets[$widget->getName()] = $widget;

        return $this;
    }
}
