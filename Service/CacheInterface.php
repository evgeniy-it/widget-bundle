<?php

namespace Evgit\Bundle\WidgetBundle\Service;

/**
 * CacheInterface.
 */
interface CacheInterface
{
    /**
     * @param string $shortCode
     *
     * @return bool
     */
    public function isCached($shortCode);

    /**
     * @param string $shortCode
     * @param string $content
     *
     * @return $this
     */
    public function setCache($shortCode, $content);

    /**
     * @param string $shortCode
     *
     * @return string
     */
    public function getCache($shortCode);
}
