<?php

namespace Neotis\Core\Router;

use Neotis\Core\Neotis;
use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Core\Mvc\Controller;
use Neotis\Core\Services\Methods;
use Neotis\Interfaces\Core\Router\Router as iRouter;

/**
 * Segmentation and processing of url addresses
 * Created by PhpStorm.
 * Date: 7/11/2017
 * Time: 4:46 PM
 * Neotis framework
 * @router class
 * @Run application
 */
class Router extends Neotis implements iRouter
{
    /**
     * Name of current language
     */
    private static $language = null;

    /**
     * Name of current language
     */
    private static $packageConfig = null;


    /**
     * Name of current package
     */
    private static $package = null;

    /**
     * Path of package mvc files
     */
    private static $packageBase = null;

    /**
     * Current controller name
     * @var null
     */
    private static $controller = null;

    /**
     * Current action name
     * @var null
     */
    private static $action = null;

    /**
     * Store parent of current package
     * @var null
     */
    private static $packageParent = null;

    /**
     * List of current arguments
     * @var array
     */
    private static $arguments = [];

    /**
     * Define not found status
     * @var bool
     */
    private static $notFound = false;

    /**
     * Store not found controller
     * @var string
     */
    private static $notFoundController = 'index';

    /**
     * Store not found action
     * @var string
     */
    private static $notFoundAction = 'notfound';

    /**
     * Store time zone
     * @var string
     */
    private static $timeZone = "";

    /**
     * Define remote access status
     * @var string
     */
    private static $remoteAccess = "";

    /**
     * Store global display status
     * @var string
     */
    private static $global = "";

    /**
     * Store programmer email or account
     * @var string
     */
    private static $programmer = "";

    /**
     * Store extendable parent controller
     * @var string
     */
    private static $extendsController = "";

    /**
     * Store classification
     * @var string
     */
    private static $classification = "";

    /**
     * Store author name or account or email
     * @var string
     */
    private static $author = "";

    /**
     * Store category
     * @var string
     */
    private static $category = "";

    /**
     * Store coverage
     * @var string
     */
    private static $coverage = "";

    /**
     * Store distribution dispaly status
     * @var string
     */
    private static $distribution = "";

    /**
     * Store revisit robot time for index period
     * @var string
     */
    private static $revisitAfter = "";

    /**
     * Store identity number of user
     * @var int
     */
    private static $userId = 1;

    /**
     * Store identity number of user group|type
     * @var int
     */
    private static $userType = 1;

    /**
     * Check if action is router or not
     * @var bool
     */
    public static $isRouter = false;

    /**
     * Return user identity number
     */
    public static function getUserId()
    {
        return self::$userId;
    }

    /**
     * Return user group identity number
     */
    public static function getUserType()
    {
        return self::$userType;
    }

    /**
     * Return time zone value
     */
    public static function getTimeZone()
    {
        return self::$timeZone;
    }

    /**
     * Return value of remote access
     */
    public static function getRemoteAccess()
    {
        return self::$remoteAccess;
    }

    /**
     * Return value of global
     */
    public static function getGlobal()
    {
        return self::$global;
    }

    /**
     * Return value of programmer
     */
    public static function getProgrammer()
    {
        return self::$programmer;
    }

    /**
     * Return value of extendable controller
     */
    public static function getExtendsController()
    {
        return self::$extendsController;
    }

    /**
     * Return value of classification
     */
    public static function getClassification()
    {
        return self::$classification;
    }

    /**
     * Return value of author
     */
    public static function getAuthor()
    {
        return self::$author;
    }

    /**
     * Return value of category
     */
    public static function getCategory()
    {
        return self::$category;
    }

    /**
     * Return value of coverage
     */
    public static function getCoverage()
    {
        return self::$coverage;
    }

    /**
     * Return value of distribution
     */
    public static function getDistribution()
    {
        return self::$distribution;
    }

    /**
     * Return value of revisit after
     */
    public static function getRevisitAfter()
    {
        return self::$revisitAfter;
    }

    /**
     * Get not found controller
     */
    public static function getNotFoundController()
    {
        return self::$notFoundController;
    }

    /**
     * Get not found controller
     */
    public static function getNotFoundAction()
    {
        return self::$notFoundAction;
    }

    /**
     * Get not found status
     * @return bool
     */
    public static function getNotFound()
    {
        return self::$notFound;
    }

    /**
     * Set not found status
     * @param $value
     * @return void
     */
    public static function setNotFound($value)
    {
        self::$notFound = $value;
        Header::add('HTTP/1.0 404 Not Found', true, 404);
    }

    /**
     * Access to language value
     */
    public static function getLanguage()
    {
        return self::$language;
    }

    /**
     * Fetch package configs
     */
    public static function getPackagesConfigs()
    {
        return self::$packageConfig;
    }

    /**
     * Access to language value
     */
    public static function getLanguageIso()
    {
        $file = APP_PATH . DS . 'app' . DS . 'Neotis' . DS . 'Languages' . DS . 'config.json';
        $config = json_decode($file, true);
        return $config['iso'][self::$language];
    }

    /**
     * Access to package value
     */
    public static function getPackage()
    {
        return self::$package;
    }

    /**
     * Access to controller value
     */
    public static function getController()
    {
        $controller = ucwords(self::$controller);
        return $controller;
    }

    /**
     * Access to action value
     */
    public static function getAction()
    {
        return self::$action;
    }

    /**
     * set controller value
     * @param string $value
     */
    public static function setController($value = '')
    {
        self::$controller = $value;
    }

    /**
     * set package value
     * @param string $value
     */
    public static function setPackage($value = '')
    {
        self::$package = $value;
    }

    /**
     * Set action value
     * @param string $value
     */
    public static function setAction($value = '')
    {
        self::$action = $value;
    }

    /**
     * Set user identity value
     * @param string $value
     */
    public static function setUserId($value = '')
    {
        self::$userId = $value;
    }

    /**
     * Set user group value
     * @param string $value
     */
    public static function setUserType($value = '')
    {
        self::$userType = $value;
    }

    /**
     * Access to arguments value
     */
    public static function getArguments()
    {
        return self::$arguments;
    }

    /**
     * Set value to arguments value
     * @param $value
     * @param string $type
     */
    public static function setArguments($value, $type = 'last')
    {
        if ($type === 'last') {
            self::$arguments[] = $value;
        } else {
            $values = [];
            $values[0] = $value;
            foreach (self::$arguments as $key => $value) {
                $values[] = $value;
            }
            self::$arguments = $values;
        }
    }

    /**
     * Convert package name to base url of package
     * @param $package
     * @return int|string
     */
    public static function packageToUrl($package)
    {
        $packagesDir = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end' . DS . 'config.json';
        $content = file_get_contents($packagesDir);
        $toArray = json_decode($content, true);
        foreach ($toArray as $key => $value) {
            $fKey = array_key_first($value);
            if($fKey == $package){
                return $key;
            }
        }
    }


    /**
     * Call controller and action with router information
     */
    private function callController()
    {
        if (!self::$notFound) {//If requested controller and action exist and not file
            $method = Request::getMethod();
            $json = Header::getJson();
            $controllerName = $this->getController();
            $actionName = $this->getAction();
            $package = $this->getPackage();
            $action = Controller::actionGenerator($actionName, $method, $json);
            $router = Controller::actionGenerator('router', $method, $json);
            if (Controller::controllerExist($controllerName, $package)) {//If controller exist
                //Prepare url parameter to execute as controller
                $className = '\\' . $controllerName . 'Controller';
                //Make object from controller as string
                $controller = new $className();
                if (Controller::actionExist($controller, $action)) {//If action exist
                    unset(self::$arguments[1]);
                    self::$notFound = false;
                    self::$isRouter = false;
                } elseif (Controller::actionExist($controller, $router)) {//If router is exist
                    Router::setArguments(self::$action, $type = 'last');
                    self::$action = 'router';
                    self::$isRouter = true;
                    self::$notFound = false;
                } else {//If action not exist
                    self::$notFound = true;
                    self::$isRouter = false;
                }

            } else {//If controller not exist
                self::$notFound = true;
            }
        }
    }

    /**
     * Register class loader for mvc structure base on address
     * @internal param $package
     */
    protected function loader()
    {
        $package = self::$package;
        $packageConfig = self::$packageConfig['packages'][$package];

        spl_autoload_register(function ($class) use ($package, $packageConfig) {
            //If request is not controller or model
            if (strpos($class, '\\')) {
                return;
            }

            //Check if this class is controller or not
            if (strpos($class, 'Controller')) {
                $controllerStatus = true;
                $modelStatus = false;
            } else {
                $controllerStatus = false;
                $modelStatus = true;
            }

            //Make the first letter of a class name uppercase
            $class = ucfirst($class);

            // separators with directory separators in the relative class name, append
            // with .php
            $controller = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end' . DS . $package . DS . 'controllers' . DS . $class . '.php';
            $baseController = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end' . DS . 'base' . DS . 'controllers' . DS . $class . '.php';

            $model = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end' . DS . $package . DS . 'models' . DS . $class . '.php';

            if (!file_exists($model) and !empty(self::$packageParent)) {
                $model = APP_PATH . 'app/mvc/back-end/base/models/' . $class . '.php';
                if (!file_exists($model)) {
                    if (isset($packageConfig['share_models']) and is_string($packageConfig['share_models'])) {
                        $model = APP_PATH . 'app/mvc/back-end/' . $packageConfig['share_models'] . DS . $class . '.php';
                    } elseif (isset($packageConfig['share_models']) and is_array($packageConfig['share_models'])) {
                        foreach ($packageConfig['share_models'] as $key => $value) {
                            $model = APP_PATH . 'app/mvc/back-end/' . $value . DS . $class . '.php';
                            if (file_exists($model)) {
                                break;
                            }
                        }
                    }
                }
            }

            // if the file exists, require it
            if (file_exists($controller)) {
                require $controller;
                if (!is_subclass_of($class, 'Neotis\Core\Mvc\Controller')) {
                    die($class . ' must be extends from Neotis\Core\Mvc\Controller');
                }
                return;
            }

            // if the file exists on base package, require it
            if (file_exists($baseController)) {
                require $baseController;
                if (!is_subclass_of($class, 'Neotis\Core\Mvc\Controller')) {
                    die($class . ' must be extends from Neotis\Core\Mvc\Controller');
                }
                self::setPackage('base');
                return;
            }

            if (file_exists($model)) {
                require $model;
                if (!is_subclass_of($class, 'Neotis\Core\Mvc\Model')) {
                    die($class . ' must be extends from Neotis\Core\Mvc\Model');
                }
                return;
            }
            if ($controllerStatus) {
                die('this controller = "' . $controller . '" is not exist!');
            } elseif ($modelStatus) {
                die("this '$class' model is not exist!");
            }
        });
    }

    /**
     * Find and merge all config.json file in packages
     */
    private function configFinder()
    {
        $packagesDir = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end';
        $baseConfig = $packagesDir . DS . 'config.json';
        $baseConfig = json_decode(file_get_contents($baseConfig), true);
        $packages = Methods::directories($packagesDir);
        $configs = [];
        foreach ($packages as $key => $value) {
            $file = $packagesDir . DS . $value . DS . 'config.json';
            if (file_exists($file)) {
                $config = file_get_contents($file);
                $config = json_decode($config, true);
                $configs['packages'][$value] = $config;
            }
        }

        $configs['base'] = $baseConfig;
        return $configs;
    }

    /**
     * If requested url base exist on package
     * @param $packages
     * @param $url
     * @return array|bool
     */
    private function existInPackages($packages, $url)
    {
        $partial = explode('/', $url);
        if (!isset($partial[1])) {
            $conf = $packages['/'];
            $finalUrl = explode('/', $url);
            if (!isset($finalUrl[1])) {
                $finalUrl[1] = '/';
            }
            return [
                'key' => key($conf),
                'url' => $finalUrl,
                'base' => '/'
            ];
        } else {
            $baseUrl = '/';
            $conf = $packages['/'];
            $finalUrl = explode($baseUrl, $url);
            if (!isset($finalUrl[1])) {
                $finalUrl[1] = '/';
            }
            $currentUrl = $finalUrl;
            unset($currentUrl[0]);
            $implodeUrl = implode('/', $currentUrl);
            $result = [
                'key' => key($conf),
                'url' => $implodeUrl,
                'base' => '/'
            ];
            $prefix = '';
            foreach ($partial as $key => $value) {
                $baseUrl .= $value . $prefix;
                if (isset($packages[$baseUrl])) {
                    $finalUrl = explode($baseUrl, $url);
                    if (!isset($finalUrl[1])) {
                        $finalUrl[1] = '/';
                    }
                    $currentUrl = $finalUrl;
                    unset($currentUrl[0]);
                    $implodeUrl = implode('/', $currentUrl);
                    $result = [
                        'key' => key($packages[$baseUrl]),
                        'url' => $implodeUrl,
                        'base' => $baseUrl
                    ];
                }
                $prefix = '/';
            }
            return $result;
        }
    }

    /**
     * Package manager
     * @param $url
     * @return mixed
     */
    private function packageManager($url)
    {
        $config = $this->configFinder();
        self::$packageConfig = $config;
        $packageResult = $this->existInPackages($config['base'], $url);
        $keyValue = $config['packages'][$packageResult['key']];
        if ($packageResult) {
            self::$package = $packageResult['key'];
            self::$packageBase = $packageResult['base'];
            self::$language = $keyValue['language'];
            if (!empty($keyValue['parent'])) {
                self::$packageParent = $keyValue['parent'];
            }
            self::$controller = $keyValue['defaults']['controller'];
            self::$action = $keyValue['defaults']['action'];
            if (isset($keyValue['notFound']) and !empty($keyValue['notFound'])) {
                self::$notFoundController = $keyValue['notFound']['controller'];
                self::$notFoundAction = $keyValue['notFound']['action'];
            }
            if (isset($keyValue['timeZone'])) {
                self::$timeZone = $keyValue['timeZone'];
            }
            if (isset($keyValue['remote_access'])) {
                self::$remoteAccess = $keyValue['remote_access'];
            }
            if (isset($keyValue['global'])) {
                self::$global = $keyValue['global'];
            }
            if (isset($keyValue['programmer'])) {
                self::$programmer = $keyValue['programmer'];
            }
            if (isset($keyValue['extends_controller'])) {
                self::$extendsController = $keyValue['extends_controller'];
            }
            if (isset($keyValue['classification'])) {
                self::$classification = $keyValue['classification'];
            }
            if (isset($keyValue['author'])) {
                self::$author = $keyValue['author'];
            }
            if (isset($keyValue['category'])) {
                self::$category = $keyValue['category'];
            }
            if (isset($keyValue['coverage'])) {
                self::$coverage = $keyValue['coverage'];
            }
            if (isset($keyValue['distribution'])) {
                self::$distribution = $keyValue['distribution'];
            }
            if (isset($keyValue['revisit_after'])) {
                self::$revisitAfter = $keyValue['revisit_after'];
            }
        }

        return $packageResult['url'];
    }

    /**
     * Rely addressing
     * @return bool
     */

    private function relyUrl()
    {
        $url = $this->seoUrl();
        $path_parts = pathinfo(end($url));
        if (isset($path_parts['extension']) and !empty($path_parts['extension'])) {//If url is file and not exist
            self::$notFound = true;
            return false;
        }
        if (isset($url[0]) and !empty($url[0])) {
            self::$controller = $url[0];
            unset($url[0]);
        }
        if (isset($url[1]) and !empty($url[1])) {
            self::$action = $url[1];
        }
        if (is_array($url)) {
            self::$arguments = $url;
        }
    }

    /**
     * Beautify addressing and optimization for access to controller and action
     */
    private function seoUrl()
    {
        $url = Request::getQuery('_url');
        $url = $this->packageManager($url);
        if (is_array($url)) {
            $urlArray = [];
        } else {
            $urlArray = explode('/', $url);
        }
        return $urlArray;
    }

    /**
     * Forward controller and action to custom selected
     * @param string $string
     * @param int $status
     */
    public static function forwarder($string = '', $status = 200)
    {
        $info = explode('/', $string);
        if (count($info) == 2) {
            Header::codesHeader($status);
            self::setController($info[0]);
            self::setAction($info[1]);
        }
    }

    /**
     * Run router
     */
    public function run()
    {
        $this->relyUrl();
        $this->loader();
        $this->callController();
    }
}
