<?php
namespace Neotis\Interfaces\Core\Session;

/**
 * Session interface
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 1:03 PM
 */

interface Adapter
{
    /**
     * Set session to application
     * @param $name
     * @param $value
     */
    public static function set($name, $value);

    /**
     * Return session value
     * @param $name
     * @return mixed
     */
    public static function get($name);


    /**
     * Unset session varibale
     * @param $array
     */
    public static function _unset($array);

    /**
     * Unserialize session information as string
     * @param $str
     * @return array
     */
    public static function unserialize($str);

    /**
     * Regenerate session id
     */
    public static function reId();

    /**
     * return session id
     * @return string
     */
    public static function getId();
}
