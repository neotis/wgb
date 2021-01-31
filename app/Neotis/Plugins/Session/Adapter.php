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

use Neotis\Core\Http\Header;
use Neotis\Plugins\Plugins;
use Neotis\Interfaces\Core\Session\Adapter as Session;
use Neotis\Plugins\Session\Handler;

class Adapter extends Plugins implements Session
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
     * If token value is exist for fetch session record
     * @var bool
     */
    public static $token = false;

    /**
     * Regenerate id status
     * @var bool
     */
    public static $reIdStatus = false;

    /**
     * Open connection to database
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        if (!self::$start) {
            self::$start = true;
            return true;
        }
        return false;
    }

    /**
     * Close connection to database
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read data and set to session
     * @param $id
     * @return array | string
     */
    public function read($id)
    {
        if (self::$token) {
            $id = self::$token;
        }

        $data = \Sessions::connect()
            ->where([
                'id' => $id
            ])
            ->find();
        if (!empty($data)) {
            return $data['data'];
        } else {
            return '';
        }
    }

    /**
     * Set data to database
     * @param $id
     * @param $data
     * @return array | string
     */
    public function write($id, $data)
    {
        if (self::$token and !self::$reIdStatus) {
            $id = self::$token;
        }

        $replace = \Sessions::connect()
            ->values([
                'data' => $data,
                'id' => $id
            ])
            ->replace();

        self::$reIdStatus = false;
        if ($replace) {
            return true;
        } else {
            return false;
        }
    }


    public function destroy($id)
    {
        if (self::$token) {
            $id = self::$token;
        }
        \Sessions::connect()
            ->where([
                'id' => $id
            ])
            ->delete();
        return true;
    }


    public function clean()
    {
        return true;
    }

    public static function unSerializeSession($data)
    {
        $vars = preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
            $data, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 0; @$vars[$i]; $i++) {
            if (isset($vars[$i])) {
                $result[$vars[$i++]] = unserialize($vars[$i]);
            }
        }
        return $result;
    }

    /**
     * Start session
     */
    public function start()
    {
        //If out default parameter is not empty
        $headers = Header::getRequests();
        if (isset($headers['X-Auth-Token'])) {
            self::$token = $headers['X-Auth-Token'];
        }
        $handler = new Handler();

        session_set_save_handler($handler, false);

        session_start([
            'cookie_lifetime' => 86400
        ]);
    }

    /**
     * Set session to application
     * @param $name
     * @param session_write_close
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

    /**
     * Regenerate session id
     */
    public static function reId()
    {
        self::$reIdStatus = true;
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
}
