<?php
/**
 * RedisCache.php
 *
 * @category   Brander
 * @package    Brander_RedisCache.php
 * @author     brander.ua
 */

namespace Evgit\Bundle\WidgetBundle\Service;

/**
 * @author Tomfun <tomfun1990@gmail.com>
 */
class RedisCache implements CacheInterface
{
    /**
     * @var ClientInterface
     */
    private $redisService;
    /**
     * @param string $shortCode
     *
     * @return bool
     */
    public function isCached($shortCode)
    {
        // TODO: Implement isCached() method.
    }

    /**
     * @param string $shortCode
     * @param string $content
     *
     * @return $this
     */
    public function setCache($shortCode, $content)
    {
        // TODO: Implement setCache() method.
    }

    /**
     * @param string $shortCode
     *
     * @return string
     */
    public function getCache($shortCode)
    {
        // TODO: Implement getCache() method.
    }

}
