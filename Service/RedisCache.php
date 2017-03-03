<?php

namespace Evgit\Bundle\WidgetBundle\Service;

use Predis\Client;

/**
 * class RedisCache
 */
class RedisCache implements CacheInterface
{
    /**
     * @var \Redis
     */
    private $cacheProvider;

    /**
     * @var string
     */
    private $cachePrefix = 'widget_';

    /**
     * @param Client $cacheProvider
     */
    public function __construct(Client $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @param string $shortCode
     *
     * @return bool
     */
    public function isCached($shortCode)
    {
        return $this->cacheProvider->exists($this->getKeyByShortCode($shortCode));
    }

    /**
     * @param string $shortCode
     * @param string $content
     * @param int    $lifeTime
     *
     * @return $this
     */
    public function setCache($shortCode, $content, $lifeTime = 0)
    {
        $id = $this->getKeyByShortCode($shortCode);
        if (0 < $lifeTime) {
            $result = $this->cacheProvider->setex($id, (int) $lifeTime, $content);
        } else {
            $result = $this->cacheProvider->set($id, $content);
        }

        return $result;
    }

    /**
     * @param string $shortCode
     *
     * @return string
     */
    public function getCache($shortCode)
    {
        if (!$this->isCached($shortCode)) {
            return false;
        }

        return $this->cacheProvider->get($shortCode);
    }

    /**
     * @param $shortCode
     * @return string
     */
    private function getKeyByShortCode($shortCode)
    {
        $shortCode = str_replace(["\n", " "], "", $shortCode);

        return $this->cachePrefix.md5($shortCode);
    }
}
