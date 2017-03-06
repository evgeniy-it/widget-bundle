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
     * @param int    $lifeTime
     *
     * @return $this
     */
    public function setCache($shortCode, $content, $lifeTime = 0);

    /**
     * @param string $shortCode
     *
     * @return string
     */
    public function getCache($shortCode);
}
