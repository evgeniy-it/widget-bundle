<?php

namespace Evgit\Bundle\WidgetBundle\Tests\Sevice;

use Evgit\Bundle\WidgetBundle\Service\Parser;
use Evgit\Bundle\WidgetBundle\Service\WidgetCollection;
use Evgit\Bundle\WidgetBundle\Widget\WidgetInterface;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider processGetProperWidgetsProvider
     */
    public function testParseNestedWidgets($count, $string)
    {
        $parser = new Parser();

        $widgetCollection = $this->getMockBuilder(WidgetCollection::class)->setMethods(['getWidget'])->getMock();
        $widgetCollection->expects($this->exactly($count))->method("getWidget")->with($this->equalTo('test'));
        $parser->setWidgetCollection($widgetCollection);
        $parser->process($string, true);
    }

    /**
     * @dataProvider parserContentProvider
     */
    public function testParserContent($stringOut, $stringIn) {
        $parser = new Parser();
        $collection = new WidgetCollection();
        $mockWidget =  $this->getMockBuilder(WidgetInterface::class)->getMock();
        $mockWidget->method("process")->will($this->returnCallback( function($options) {return implode(",",$options);}));
        $mockWidget->method("getName")->will($this->returnValue("mocker"));
        $collection->addWidget($mockWidget);
        $parser->setWidgetCollection($collection);

        $this->assertEquals($parser->process($stringIn), $stringOut);
    }

    public function parserContentProvider()
    {
        return  [
            ["1", "[[mocker? &param1=`1`]]"],
            ["1,2", "[[mocker? &param1=`1` &param2=`2`]]"],
            ["1,1,2", "[[mocker? &param1=`1` &param2=`[[mocker? &param1=`1` &param2=`2`]]`]]"],
            ["1,2,3,4", "[[mocker? &param1=`1` &param3=`2` &param2=`[[mocker? &param1=`3` &param2=`[[mocker? &param1=`4`]]`]]`]]"],
            ["===1===", "===[[mocker? &param1=`1`]]==="],
        ];
    }

    public function processGetProperWidgetsProvider() {

        return [
            [0, ""],
            [1, "test[[test]]test test"],
            [1, "test[[test?]]"],
            [1, "test[[test? &content=``]]test"],
            [2, "test[[test? &content=`some text[[test? &content=``]]some text`]]test"],
            [3, "test[[test? &content=`some text[[test? &content=`[[test]]`]]some text`]]test"],
            [4, "test[[test? &content=`[[test?&content=`[[test?&content=`[[test?&content=`test` ]]1` ]]` ]]` ]]test"],
            [5, "some test[[test? &content=`some text[[test? &content=`[[test]]` &test=`[[test?&content=`[[test]]`]]`]]some text`]]"],
            [7, "[[test? &content=`[[test? &content=`[[test? &content=`[[test? &content=`[[test? &content=`[[test? &content=`[[test? &content=``]]`]]`]]`]]`]]`]]`]]"],
        ];
    }
}