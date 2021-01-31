<?php
/**
 * Generate cache file for "Template Factory"
 * Created by PhpStorm.
 * Date: 3/15/2019
 * Time: 7:54 PM
 */
namespace Neotis\Core\Cache;

use Neotis\Core\Mvc\View as MvcView;
use Neotis\Core\Router\Router;

class View
{
    /**
     * Store status and time of selected page cache
     * @var array
     */
    private static $cachedStatus = [];

    /**
     * Create cache file for output
     * @param $path
     * @param $content
     */
    public static function makeCache($path, $content)
    {
        $path = str_ireplace('manufactured' . DS  . 'result', 'manufactured' . DS  . 'cache', $path);
        $info = pathinfo($path);
        @mkdir($info['dirname'] , 0755, true);
        file_put_contents($path, $content);
    }

    /**
     * Check if cache file exist and run it
     */
    public static function cacheChecker()
    {
        $package = Router::getPackage();
        $device = MvcView::getDevice();
        $controller = strtolower(Router::getController());
        $action = strtolower(Router::getAction());

        $json = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Cache' . DS . 'history.json';
        if(file_exists($json)){
            $content = file_get_contents($json);
            $current = json_decode($content, true);
            if(isset($current['current'][$package][$device][$controller][$action], $current['time'][$package][$device][$controller][$action], $current['time'])
                and (time() - $current['current'][$package][$device][$controller][$action]) < $current['time'][$package][$device][$controller][$action]){
                $file = APP_PATH . DS . 'manufactured' . DS . 'cache' . DS . $package . DS . $device . DS . $controller . DS . $action . '.phtml';
                if(file_exists($file)){
                    include $file;
                    die;
                }
            }
        }
    }

    /**
     * Define page cached status
     * @param $package
     * @param $controller
     * @param $action
     * @param $device
     * @param $time
     */
    public static function setCache($package, $device, $controller, $action, $time)
    {
        $json = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Cache' . DS . 'history.json';
        $content = file_get_contents($json);
        $current = json_decode($content, true);
        $current['time'][$package][$device][$controller][$action] = $time;
        $content = json_encode($current);
        file_put_contents($json, $content);
        self::$cachedStatus = $content;

    }

    /**
     * Create cache history file
     * @param $package
     * @param $controller
     * @param $action
     * @param $device
     * @param $time
     */
    public static function currentCache($package, $device, $controller, $action, $time)
    {
        $json = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Cache' . DS . 'history.json';
        $content = file_get_contents($json);
        $current = json_decode($content, true);
        $controller = strtolower($controller);
        $action = strtolower($action);
        $current['current'][$package][$device][$controller][$action] = $time;
        $content = json_encode($current);
        file_put_contents($json, $content);
    }

    /**
     * Define timer for template factory cache system
     * @param $controller
     * @param $action
     * @param $time
     */
    public static function setCacheStatus($controller, $action, $time)
    {
        $package = Router::getPackage();
        $device = MvcView::getDevice();
        self::setCache($package, $device, $controller, $action, $time);
    }

    /**
     * Store in cache component variables calculated by html
     * @param $path
     * @param $variables
     */
    public static function componentVariables($path, $variables)
    {
        $path = str_ireplace('manufactured' . DS  . 'result', 'manufactured' . DS  . 'component-vars', $path);
        $info = pathinfo($path);
        @mkdir($info['dirname'] , 0755, true);
        $variables = json_encode($variables, JSON_UNESCAPED_UNICODE);
        file_put_contents($path, $variables);
    }

    /**
     * Read component variables calculated by html cache
     * @param $path
     * @return mixed
     */
    public static function readComponentVariables($path)
    {
        $path = str_ireplace('manufactured' . DS  . 'result', 'manufactured' . DS  . 'component-vars', $path);
        $content = file_get_contents($path);
        $result = json_decode($content);
        return $result;
    }
}
