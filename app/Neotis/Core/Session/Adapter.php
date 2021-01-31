<?php
/**
 * Session adapter
 * Created by PhpStorm.
 * User: developer
 * Date: 7/12/2017
 * Time: 2:26 PM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Core\Session;

use Neotis\Core\Neotis;

class Adapter extends Neotis implements \Neotis\Interfaces\Core\Session\Adapter
{
    /**
     * Status of start session
     * @var bool
     */
    private static $start = false;


    /**
     * Status of stop
     * @var bool
     */
    public static $stop = false;

    /**
     * Start session
     */
    public static function start()
    {
        if (!self::$start) {
            self::$start = true;
            session_start();
        }
    }

    /**
     * Set session to application
     * @param $name
     */
    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Return session value
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }


    /**
     * Unset session variable
     * @param $array
     */
    public static function _unset($array)
    {
        foreach ($array as $key => $value) {
            unset($_SESSION[$value]);
        }
    }

    /**
     * Regenerate session id
     */
    public static function reId()
    {
        session_regenerate_id(true);
    }

    /**
     * return session id
     * @return string
     */
    public static function getId()
    {
        return session_id();
    }

    /**
     * Unserialize session information as string
     * @param $str
     * @return array
     */
    public static function unserialize($str)
    {
        $array = [];
        while ($i = strpos($str, '|')) {
            $k = substr($str, 0, $i);
            $v = unserialize(substr($str, 1 + $i));
            $str = substr($str, 1 + $i + strlen(serialize($v)));
            $array[$k] = $v;
        }
        return $array;
    }
}
