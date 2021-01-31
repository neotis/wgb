<?php

/**
 * Cookie adapter
 * Created by PhpStorm.

 * Date: 8/18/2017
 * Time: 8:01 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Cookie;

use Neotis\Core\Neotis;
use Neotis\Core\Services\Methods;
use Neotis\Interfaces\Core\Cookie\Adapter as Cookie;

class Adapter extends Neotis implements Cookie
{
    /**
     * Private static properties area
     */
    private static $cookieTime = 0;

    /**
     * Adapter constructor.
     * @param int $time
     */
    public function __construct($time = 0)
    {
        if ($time > 0) {
            self::$cookieTime = ($time * 86400);
        }
    }

    /**
     * Set cookie
     * @param string $name
     * @param string $values
     * @param bool $time
     * @param string $path
     * @internal param string $key
     * @internal param string $value
     * @internal param string $name
     */
    public static function set($name = '', $values = '', $time = false, $path = '/')
    {
        if (!$time) {
            $time = self::$cookieTime;
        }
        if (is_array($values)) {
            $values = json_encode($values);
        }

        $key = Methods::unique();
        \Cookies::connect()
            ->values([
                'ckey' => $key,
                'data' => $values
            ])
            ->add();
        $result = setcookie($name, $key, time() + $time, $path);
    }

    /**
     * Get cookie name
     * @param string $name
     * @return mixed
     */
    public static function get($name = '')
    {
        if (!empty($name)) {
            if (isset($_COOKIE[$name])) {
                //first find current selected cookie
                $cookie = \Cookies::connect()
                    ->where([
                        'ckey' => $_COOKIE[$name],
                        'deleted' => 0
                    ])
                    ->find();

                //If selected cookie exist on our database
                if ($cookie) {
                    $values = $cookie['data'];

                    //Delete current cookie
                    \Cookies::connect()
                        ->where([
                            'ckey' => $_COOKIE[$name]
                        ])
                        ->delete();

                    //Set new cookie
                    self::set($name, $values);

                    //Check if data string is json or not then return values
                    if (Methods::isJson($values)) {
                        $values = json_decode($values, true);
                    }
                    return $values;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return $_COOKIE;
    }
}
