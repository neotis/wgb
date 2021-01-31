<?php
/**
 * Created by PhpStorm.

 * Date: 10/28/2018
 * Time: 1:04 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;


use Neotis\Core\Cache\Data;

trait Cache
{
    /**
     * Store cache status
     * @var bool
     */
    protected $cacheStatus = false;

    /**
     * Store cache status
     * @var bool
     */
    protected $cacheResultStatus = false;


    /**
     * Store cache time
     * @var string
     */
    protected $cacheTime = '';


    /**
     * Store ache object
     * @var array
     */
    protected $cache = [];

    /**
     * @var string
     * Store content of result
     */
    protected $cacheContent = '';

    /**
     * Make cache status
     * @param $name
     * @param $time
     * @param $user
     * @param $group
     * @param $query
     */
    protected function makeCache($time)
    {
        $this->cacheStatus = true;
        $this->cache = new Data();
        $this->cacheTime = $time;
    }

    /**
     * Check cache status
     * @param $query
     * @param $values
     */
    protected function checkCache($query, $values)
    {
        $result = $this->cache->fetch($query, $values);
        $this->cacheResultStatus = $result['status'];
        $this->cacheContent = $result['content'];
    }

    /**
     * Create cache content
     * @param $query
     * @param $values
     * @param $content
     * @param $time
     */
    protected function generateCache($query, $values, $content)
    {
        $this->cache->do($query, $values, $content, $this->cacheTime);
    }
}
