<?php
/**
 * Events jobs interface
 * Created by PhpStorm.

 * Date: 10/4/2018
 * Time: 2:41 PM
 * Neotis framework
 */

namespace Neotis\Interfaces\Core\Events;


interface Jobs
{
    /**
     * Define events job
     * @param $name
     * @param $job
     */
    public static function fire($name, $job);

    /**
     * Define jobs object and event
     * @param $name
     * @param $object
     * @param $method
     * @param array $arguments
     * @param string $comment
     */
    public static function set($name, $object, $method, $arguments = [], $comment = '', $namesapces);

    /**
     * Prioritizing events and jobs
     */
    public function prioritize();
}
