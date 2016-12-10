<?php

namespace Evgit\Bundle\WidgetBundle\Tests\Widgets;
use Evgit\Bundle\WidgetBundle\Widget\AbstractWidget;

/**
 * Class AbstractWidgetTest
 * @package Evgit\Bundle\WidgetBundle\Tests\Sevice
 */
class AbstractWidgetTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $abstractWidget = $this->getMockForAbstractClass(AbstractWidget::class);
        $abstractWidget->setTemplate("{{content1}}-{{content2}}");
        $result = $abstractWidget->render(["content1" => "test1", "content2" => "test2"]);

        $this->assertEquals("test1-test2", $result);
    }
}
