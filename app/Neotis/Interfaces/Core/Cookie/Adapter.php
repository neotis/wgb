<?php
namespace Neotis\Interfaces\Core\Cookie;

/**
 * Cookie interface
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 1:03 PM
 */

interface Adapter
{
    /**
     * Set cookie to application
     * @param $name
     * @param $value
     */
    public static function set($name, $value);

    /**
     * Return cookie value
     * @param $name
     * @return mixed
     */
    public static function get($name);
}
