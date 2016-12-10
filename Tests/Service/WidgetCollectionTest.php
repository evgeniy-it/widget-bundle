<?php

namespace Evgit\Bundle\WidgetBundle\Tests\Sevice;

use Evgit\Bundle\WidgetBundle\Service\WidgetCollection;
use Evgit\Bundle\WidgetBundle\Widget\WidgetInterface;

/**
 * Class WidgetCollectionTest
 * @package Evgit\Bundle\WidgetBundle\Tests\Sevice
 */
class WidgetCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testAddWidgetSameName()
    {
        $collection = new WidgetCollection();
        $widget = $this->getMockBuilder(WidgetInterface::class)->getMock();
        $widget->method("getName")->will($this->returnValue("mocker"));

        $widgetSameName = $this->getMockBuilder(WidgetInterface::class)->getMock();
        $widgetSameName->method("getName")->will($this->returnValue("mocker"));

        $collection->addWidget($widget);
        $collection->addWidget($widgetSameName);
    }

    /**
     * @expectedException \Exception
     */
    public function testAddWidgetWithNoName()
    {
        $collection = new WidgetCollection();
        $widget = $this->getMockBuilder(WidgetInterface::class)->getMock();

        $collection->addWidget($widget);
    }
}
