<?php
/**
 * Generate cache file for "Database"
 * Created by PhpStorm.
 * Date: 3/15/2019
 * Time: 7:54 PM
 */

namespace Neotis\Core\Cache;

use Neotis\Core\Http\Request;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

class Disk
{
    /**
     * Store hash name for make cache
     * @var string
     */
    public $hashName = false;

    /**
     * Store path file for create cache
     * @var string
     */
    public $path = false;

    /**
     * Create name of cache file
     * @param $controller
     * @param $action
     * @param $name
     * @param $data
     * @param $time
     * @param $user
     * @param $group
     * @return array
     */
    private function nameCreator($name, $user, $group, $query)
    {
        $package = Router::getPackage();
        $groupId = Router::getUserType();
        $userId = Router::getUserId();

        if (!$query) {
            $query = [];
        }

        $query = md5(Methods::jsonOut($query));
        $dir = APP_PATH . 'cache' . DS . 'data' . DS . $package . DS . $controller . DS . $action . DS;

        $name = $package . '-' . $controller . '-' . $action . '-' . $name . '-' . $query;

        if ($group) {
            $name .= '-' . $groupId;
        }

        if ($user) {
            $name .= '-' . $userId;
        }

        $name = md5($name);

        return ['dir' => $dir, 'name' => $dir . $name . '.json', 'conf' => $dir . $name . '.conf.json'];
    }

    /**
     * Check type of data
     * @param $data
     * @return string
     */
    private function checkDataType($data)
    {
        if (is_array($data)) {
            return 'array';
        } elseif (is_string($data)) {
            return 'string';
        } elseif (is_integer($data)) {
            return 'integer';
        } elseif (is_bool($data)) {
            return 'bool';
        }
    }

    /**
     * Create config file
     * @param $conf
     * @param $time
     * @param $content
     */
    private function makeConfig($conf, $time, $content)
    {
        $type = $this->checkDataType($content);
        $data = [
            'register' => time(),
            'time' => $time,
            'type' => $type
        ];
        $data = Methods::jsonOut($data);
        file_put_contents($conf, $data);
    }

    /**
     * Check config file
     * To check the remaining time of the cache
     * And how to decide on cache conditions
     * @param $conf
     * @param $data
     * @return array
     */
    private function checkConf($conf)
    {
        if (is_file($conf)) {
            $read = file_get_contents($conf);
            $data = json_decode($read, true);
            if ((time() - $data['register']) > $data['time']) {
                return ['status' => false, 'type' => []];
            } else {
                return ['status' => true, 'type' => $data['type']];
            }
        } else {
            return ['status' => false, 'type' => []];
        }
    }

    /**
     * Read cache data from disk
     * @param $file
     * @param $type
     * @return bool|false|int|mixed|string
     */
    private function read($file, $type)
    {
        $content = file_get_contents($file);
        switch ($type) {
            case "array":
                return json_decode($content, true);
                break;
            case "string":
                return $content;
                break;
            case "integer":
                return ((int)$content);
                break;
            case "bool":
                if ($content == 0) {
                    return false;
                } else {
                    return true;
                }
                break;
        }
    }

    /**
     * Wrtie cache content to disk
     * @param $file
     * @param $content
     */
    private function write($file, $content)
    {
        $type = $this->checkDataType($content);
        switch ($type) {
            case "array":
                $content = Methods::jsonOut($content);
                file_put_contents($file, $content);
                break;
            case "string":
                file_put_contents($file, $content);
                break;
            case "integer":
                file_put_contents($file, (int)$content);
                break;
            case "bool":
                if ($content == false) {
                    file_put_contents($file, 0);
                } else {
                    file_put_contents($file, 1);
                }
                break;
        }
    }

    /**
     * Create cache point and restore data
     * @param $controller
     * @param $action
     * @param $name
     * @param $data
     * @param $time
     * @param bool $user
     * @param bool $group
     * @param bool $query
     */
    public function do($name, $data, $time, $user = false, $group = false, $query = false)
    {
        $name = $this->nameCreator($name, $user, $group, $query);

        if (!is_dir($name['dir'])) {
            @mkdir($name['dir'], 0777, true);
        }
        $this->makeConfig($name['conf'], $time, $data);
        $this->write($name['name'], $data);
    }

    /**
     * Fetch cache data
     * @param $controller
     * @param $action
     * @param $name
     * @param bool $user
     * @param bool $group
     * @param bool $query
     * @return array
     */
    public function fetch($controller, $action, $name, $user = false, $group = false, $query = false)
    {
        $name = $this->nameCreator($controller, $action, $name, $user, $group, $query);

        $result = $this->checkConf($name['conf']);

        if ($result['status'] and is_file($name['name'])) {
            $data = $this->read($name['name'], $result['type']);
            return ['status' => true, 'content' => $data];
        } else {
            return ['status' => false, 'content' => []];
        }
    }
}
