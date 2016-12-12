<?php

namespace Evgit\Bundle\WidgetBundle\Service;

/**
 * class Parser
 */
class Parser
{
    const PARSER_FULL_CODE = 0;

    const PARSER_PREPARED = 1;

    const PARSER_WIDGET = 2;

    const PARSER_OPTIONS = 3;

    /**
     * @var WidgetCollection
     */
    private $widgetCollection;

    /**
     * @param string    $string
     * @param bool|true $processRecursive
     *
     * @return string
     */
    public function process($string, $processRecursive = true)
    {
        $shortCodes = $this->parse($string);

        if (!count($shortCodes)) {
            return $string;
        }

        array_walk($shortCodes, [$this, "parseOptions"]);

        if (true === $processRecursive) {
            foreach ($shortCodes as &$shortCode) {
                $shortCode[self::PARSER_OPTIONS] = array_map([$this, 'process'], $shortCode[self::PARSER_OPTIONS]);
            }
        }

        for ($n = 0; $n < count($shortCodes); $n++) {
            if ($widget = $this->widgetCollection->getWidget($shortCodes[$n][self::PARSER_WIDGET])) {
                $options = $shortCodes[$n][self::PARSER_OPTIONS];
                $string = str_replace($shortCodes[$n][self::PARSER_FULL_CODE], $widget->process($options), $string);
            }
        }

        return $string;
    }

    /**
     * @param WidgetCollection $widgetCollection
     *
     * @return $this
     */
    public function setWidgetCollection(WidgetCollection $widgetCollection)
    {
        $this->widgetCollection = $widgetCollection;

        return $this;
    }

    /**
     * taken from modx CMS
     *
     * @param string $options
     */
    protected function parseOptions(&$options)
    {
        $string = $options[self::PARSER_PREPARED];
        $options[self::PARSER_WIDGET] = strpos($string, "?") === false
            ? $string
            : substr($string, 0, strpos($string, "?"));

        $properties = array();
        $tagProps = $this->escSplit("&", $string);

        foreach ($tagProps as $prop) {
            $property = $this->escSplit('=', $prop);
            if (count($property) == 2) {
                $propName = $property[0];
                if (substr($propName, 0, 4) == "amp;") {
                    $propName = substr($propName, 4);
                }
                $propValue = $property[1];
                $pvTmp = $this->escSplit(';', $propValue);
                if ($pvTmp && isset($pvTmp[1])) {
                    $propValue = $pvTmp[0];
                }
                if ($propValue[0] == '`' && $propValue[strlen($propValue) - 1] == '`') {
                    $propValue = substr($propValue, 1, strlen($propValue) - 2);
                }
                $propValue = str_replace("``", "`", $propValue);

                $properties[$propName] = $propValue;

            }
        }
        $options[self::PARSER_OPTIONS] = $properties;
    }

    /**
     * @param string $char
     * @param string $str
     * @param string $escToken
     * @param int    $limit
     *
     * @return array
     */
    protected function escSplit($char, $str, $escToken = '`', $limit = 0)
    {
        $split = [];
        $specSymmbols = [];
        $parseLevel = 0;
        $charPos = strpos($str, $char);
        if ($charPos !== false) {
            if ($charPos === 0) {
                $searchPos = 1;
                $startPos = 1;
            } else {
                $searchPos = 0;
                $startPos = 0;
            }
            $escOpen = false;
            $strlen = strlen($str);
            for ($i = $startPos; $i <= $strlen; $i++) {
                if ($i == $strlen) {
                    $tmp = trim(substr($str, $searchPos));
                    if (!empty($tmp)) {
                        $split[] = $tmp;
                    }
                    break;
                }

                if (in_array($str[$i], [$char, $escToken])) {
                    $specSymmbols[] = $str[$i];
                }

                if ($str[$i] == $escToken) {
                    if ($str[$i] === $escToken) {
                        if (array_slice($specSymmbols, -2, 1) == [$char]) {
                            $parseLevel++;
                        } else {
                            $parseLevel--;
                        }
                    }
                    if ($parseLevel === 0) {
                        $escOpen = !$escOpen;
                    } else {
                        $escOpen = true;
                    }
                    continue;
                }

                if (!$escOpen && $str[$i] == $char) {
                    $tmp = trim(substr($str, $searchPos, $i - $searchPos));
                    if (!empty($tmp)) {
                        $split[] = $tmp;
                        if ($limit > 0 && count($split) >= $limit) {
                            break;
                        }
                    }
                    $searchPos = $i + 1;
                }
            }
        } else {
            $split[] = trim($str);
        }

        return $split;
    }

    /**
     * taken from modx CMS
     *
     * @param string $origContent
     * @param string $prefix
     * @param string $suffix
     *
     * @return array
     */
    protected function parse($origContent, $prefix = '[[', $suffix = ']]')
    {
        $matches = [];
        $matchCount = 0;
        if (!empty ($origContent) && is_string($origContent) && strpos($origContent, $prefix) !== false) {
            $openCount = 0;
            $offset = 0;
            $openPos = 0;
            $closePos = 0;
            if (($startPos = strpos($origContent, $prefix)) === false) {
                return $matches;
            }
            $offset = $startPos + strlen($prefix);
            if (($stopPos = strrpos($origContent, $suffix)) === false) {
                return $matches;
            }
            $stopPos = $stopPos + strlen($suffix);
            $length = $stopPos - $startPos;
            $content = $origContent;
            while ($length > 0) {
                $openCount = 0;
                $content = substr($content, $startPos);
                $openPos = 0;
                $offset = strlen($prefix);
                if (($closePos = strpos($content, $suffix, $offset)) === false) {
                    break;
                }
                $nextOpenPos = strpos($content, $prefix, $offset);
                while ($nextOpenPos !== false && $nextOpenPos < $closePos) {
                    $openCount++;
                    $offset = $nextOpenPos + strlen($prefix);
                    $nextOpenPos = strpos($content, $prefix, $offset);
                }
                $nextClosePos = strpos($content, $suffix, $closePos + strlen($suffix));
                while ($openCount > 0 && $nextClosePos !== false) {
                    $openCount--;
                    $closePos = $nextClosePos;
                    $nextOpenPos = strpos($content, $prefix, $offset);
                    while ($nextOpenPos !== false && $nextOpenPos < $closePos) {
                        $openCount++;
                        $offset = $nextOpenPos + strlen($prefix);
                        $nextOpenPos = strpos($content, $prefix, $offset);
                    }
                    $nextClosePos = strpos($content, $suffix, $closePos + strlen($suffix));
                }
                $closePos = $closePos + strlen($suffix);

                $outerTagLength = $closePos - $openPos;
                $innerTagLength = ($closePos - strlen($suffix)) - ($openPos + strlen($prefix));

                $matches[$matchCount][0] = substr($content, $openPos, $outerTagLength);
                $matches[$matchCount][1] = substr($content, ($openPos + strlen($prefix)), $innerTagLength);
                $matchCount++;

                if ($nextOpenPos === false) {
                    $nextOpenPos = strpos($content, $prefix, $closePos);
                }
                if ($nextOpenPos !== false) {
                    $startPos = $nextOpenPos;
                    $length = $length - $nextOpenPos;
                } else {
                    $length = 0;
                }
            }
        }

        return $matches;
    }
}
