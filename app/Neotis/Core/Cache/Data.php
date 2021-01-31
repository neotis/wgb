<?php
/**
 * Generate cache file for "Disk data"
 * Created by PhpStorm.
 * Date: 3/15/2019
 * Time: 7:54 PM
 */

namespace Neotis\Core\Cache;

use Neotis\Core\Http\Request;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

class Data
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
     * Store select query
     * @var string
     */
    public $query = '';

    /**
     * Create name of cache file
     * @param $query
     * @param $values
     * @return array
     */
    private function nameCreator($query, $values)
    {
        $string = '';

        foreach ($values as $key => $value) {
            $string .= $key . $value;
        }
        $hash = hash('ripemd160', $query . $string);

        $file = APP_PATH . 'cache' . DS;

        $dir = $file;

        return ['dir' => $dir, 'name' => $dir . $hash . '.json', 'conf' => $dir . $hash . '.conf.json'];
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
            case "string":
                return $content;
            case "integer":
                return ((int)$content);
            case "bool":
                if ($content == 0) {
                    return false;
                } else {
                    return true;
                }
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
     * @param bool $query
     * @param $values
     * @param $time
     */
    public function do($query, $values, $content, $time)
    {
        $name = $this->nameCreator($query, $values);

        if (!is_dir($name['dir'])) {
            @mkdir($name['dir'], 0777, true);
        }
        $this->makeConfig($name['conf'], $time, $content);
        $this->write($name['name'], $content);
    }

    /**
     * Fetch cache data
     * @param bool $query
     * @param $values
     * @return array
     */
    public function fetch($query, $values)
    {
        $name = $this->nameCreator($query, $values);

        $result = $this->checkConf($name['conf']);
        if ($result['status'] and is_file($name['name'])) {
            $data = $this->read($name['name'], $result['type']);
            return ['status' => true, 'content' => $data];
        } else {
            return ['status' => false, 'content' => []];
        }
    }
}
