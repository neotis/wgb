<?php

/**
 * Neotis view rely
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Core\Mvc;

use Neotis\Core\Exception\Exception;
use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Core\Neotis;
use Neotis\Core\Router\Router;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Neotis\Core\Template\Factory as Template;
use Neotis\Core\Cache\View as Cache;
use Neotis\Core\Ini\Display as BaseConfig;
use Neotis\Core\Watcher\Manage as Watcher;

class View extends Neotis
{
    /**
     * Store name of main file for render template
     * @var string
     */
    private static $main = '';

    /**
     * Store main index value
     * @var string
     */
    private static $mainIndex = 'index';

    /**
     * Store name of content file for inner render
     * @var string
     */
    private static $content = '';

    /**
     * Store type of user device
     * @var string
     */
    private static $device = 'desktop';

    /**
     * Store name of view base directory
     * @var string
     */
    private static $baseDirectory = '';

    /**
     * Store path of result base directory
     * @var string
     */
    private static $resultBaseDirectory = '';

    /**
     * Store path of result file
     * @var string
     */
    private static $resultFile = '';


    /**
     * Return type of user device
     */
    public static function getDevice()
    {
        return self::$device;
    }

    /**
     * Return value of main index
     */
    public static function getMainIndex()
    {
        return self::$mainIndex;
    }

    /**
     * Define value for main index
     * @param string $name
     */
    public static function setMainIndex($name = 'index')
    {
        self::$mainIndex = $name;
    }

    /**
     * Define main
     * @param string $name
     */
    private static function setMain($name = 'index')
    {
        self::$main = self::$baseDirectory . 'main' . DS;
    }

    /**
     * Get main file of view
     * @return string
     */
    public static function getMain()
    {
        return self::$main;
    }

    /**
     * Define main phtml content
     * @param $controller
     * @param $action
     */
    private static function setContent($controller, $action)
    {
        $content = self::$baseDirectory . 'controllers' . DS;
        $content .= strtolower($controller) . DS . strtolower($action) . DS;
        self::$content = $content;
    }

    /**
     * Get content file of view
     * @return string
     */
    public static function getContent()
    {
        return self::$content;
    }

    /**
     * Define base directory of views
     * @param $package
     */
    private static function setBase($package)
    {
        $base = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $package . DS . self::getDevice() . DS;
        self::$baseDirectory = $base;
    }

    /**
     * Return view factory directory of base package
     */
    public static function getBasePackageDirectory()
    {
        $base = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base' . DS . self::getDevice() . DS;
        return $base;
    }

    /**
     * Get path of view base directory
     * @return string
     */
    public static function getBase()
    {
        return self::$baseDirectory;
    }

    /**
     * Define base directory of view results file
     * @param $package
     */
    private static function setResultBase($package)
    {
        self::$resultBaseDirectory = APP_PATH . 'manufactured' . DS . 'result' . DS . $package . DS;
    }


    /**
     * Get path of result base directory
     * @return string
     */
    public static function getResultBase()
    {
        return self::$resultBaseDirectory;
    }

    /**
     * Define result file path
     * @param $controller
     * @param $action
     * @return string
     */
    private static function setResultFile($controller, $action)
    {
        self::$resultFile = self::$resultBaseDirectory . self::getDevice() . DS . $controller . DS . $action . '.phtml';
    }

    /**
     * Get path of result file
     */
    public static function getResultFile()
    {
        return self::$resultFile;
    }

    /**
     * Run and define view instructor
     */
    public function run()
    {
        $package = Router::getPackage();
        $controller = Router::getController();
        $action = Router::getAction();

        self::setBase($package);
        self::setResultBase($package);
        self::setMain();
        self::setContent($controller, $action);
        self::setResultFile($controller, $action);
    }

    /**
     * Send result file for View engine and display to with web browser
     */
    public function display()
    {
        if (!Header::getJson()) {
            @ob_clean();
            $package = Router::getPackage();
            $device = View::getDevice();
            $controller = Router::getController();

            $action = Router::getAction();
            $result = View::getResultFile();
            $config = Router::getPackagesConfigs();
            $frameworkConfig = BaseConfig::$settings;

            foreach (Component::$vars as $key => $value) {
                ${$key} = $value;
            }

            if (((strtolower($frameworkConfig['default']['developer']) == true and Router::getUserId() == '2') or strtolower($frameworkConfig['default']['developer']) == 2) or Watcher::$watcher) {
                foreach (Component::$tempVars as $key => $value) {
                    foreach ($value as $iKey => $iValue) {
                        ${$key}[$iKey] = $iValue;
                    }
                }
                Cache::componentVariables($result, Component::$tempVars);
            } else {
                Component::$tempVars = Cache::readComponentVariables($result);
                foreach (Component::$tempVars as $key => $value) {
                    foreach ($value as $iKey => $iValue) {
                        ${$key}[$iKey] = $iValue;
                    }
                }
            }

            if (Header::$httpCode != 403 and Header::$httpCode != 401 and Header::$httpCode != 500 and Header::$httpCode != 503) {
                Header::add('Page-Title: ' . Template::getPureTitle());
            }

            if (!Header::$partial and !Header::$component) {
                $path = $result;
            } elseif (Header::$component) {
                if (Header::$pure) {
                    $result = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'pure-component', $result);
                }else{
                    $result = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'component', $result);
                }
                $path = $result;
            } else {
                if (Header::$pure) {
                    $result = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'pure-partial', $result);
                } else {
                    $result = str_replace('manufactured' . DS . 'result', 'manufactured' . DS . 'partial', $result);
                }
                $path = $result;
            }

            if (isset($config['packages'][$package]['cache']) and $config['packages'][$package]['cache'] == 'true') {
                ob_start();
            }

            if (isset($config['packages'][$package]['twig']) and $config['packages'][$package]['twig'] == 'true') {
                $result = pathinfo($result);
                $loader = new \Twig\Loader\FilesystemLoader($result['dirname']);
                $twig = new \Twig\Environment($loader);
                try {
                    echo $twig->render($result['filename'] . '.phtml', Template::$variables);
                } catch (LoaderError $e) {

                } catch (RuntimeError $e) {

                } catch (SyntaxError $e) {

                }
            } else {
                foreach (Template::$variables as $key => $value) {
                    ${$key} = $value;
                }
                if (is_file($path)) {
                    if (!Request::$watcher) {
                        include $path;
                    }
                } else {
                    throw new Exception('The selected view path is not exist: ' . $path);
                }
            }

            if (isset($config['packages'][$package]['cache']) and $config['packages'][$package]['cache'] == 'true') {
                $out = ob_get_clean();
                Cache::makeCache($path, $out);
                Cache::currentCache($package, $device, $controller, $action, time());
                echo $out;
            }
        }
    }
}
