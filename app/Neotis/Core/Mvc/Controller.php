<?php

/**
 * Neotis controller rely
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Core\Mvc;

use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Core\Neotis;
use Neotis\Core\Exception\Exception;
use Neotis\Core\Router\Router;

class Controller extends Neotis
{
    /**
     * Generate action name method for call with forwarder
     * @param $action
     * @param $method
     * @param $json
     * @return string
     */
    public static function actionGenerator($action, $method, $json)
    {
        if ($method === 'GET') {//If request method is GET to display data
            $result = $action . 'Action';
        } else {
            $result = strtolower($method) . $action;
        }

        if ($json) {//If the request requires a Json response
            $result .= 'Json';
        }

        return $result;
    }

    /**
     * If controller exist on current package
     * @param $controller
     * @param $package
     * @return bool
     */
    public static function controllerExist($controller, $package)
    {
        $controller = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end' . DS . $package . DS . 'controllers' . DS . $controller . 'Controller.php';
        if (file_exists($controller)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If method exist on selected controller
     * @param $controller
     * @param $action
     * @return bool
     */
    public static function actionExist($controller, $action)
    {
        $result = method_exists($controller, $action);
        return $result;
    }

    /**
     * Create new controller in current package
     * @param $name
     * @param $method
     * @param string $parent
     */
    public static function create($name, $parent = 'ControllerBase', $method = '')
    {

    }

    /**
     * Execute called method in controller
     * @param $controller
     * @param $action
     * @param $arguments
     * @return bool
     * @throws Exception
     */
    public static function callMethod($controller, $action, $arguments)
    {
        if ($controller != 'ControllerBase') {
            //Prepare url parameter to execute as controller
            $controllerName = '\\' . $controller . 'Controller';
        } else {
            $controllerName = $controller;
        }
        //Make object from controller as string
        $controller = new $controllerName();
        $parent = get_parent_class($controller);
        $base = new $parent();
        $baseInitial = method_exists($base, 'initial');
        $controllerInitial = method_exists($controller, 'initial');
        if ($baseInitial) {
            call_user_func_array(array($base, 'initial'), []);
        }
        if ($controllerInitial) {
            call_user_func_array(array($controller, 'initial'), []);
        }
        $methodExist = method_exists($controller, $action);
        $package = Router::getPackage();
        if ($methodExist) {
            return call_user_func_array(array($controller, $action), $arguments);
        } elseif (self::detectComponent($action)) {
            return true;
        } else {
            throw new Exception('The selected method: ' . $action . ' is not exist on ' . $controllerName . ' from ' . $package . ' package');
        }
    }

    /**
     * Detect if method is component or not
     * @param $action
     * @return bool
     */
    private static function detectComponent($action)
    {
        $result = preg_split('/[A-Z]/', $action);
        $counter = count($result);

        if ($result[$counter - 1] === 'omponent') {
            return true;
        }

        return false;
    }

    /**
     * Select display page based on http status code
     */
    private function basedOnHttpCode()
    {
        $package = Router::getPackage();
        $httpCode = Header::$httpCode;
        $method = Request::getMethod();
        $json = Header::getJson();
        $action = '_' . $httpCode;
        if ($httpCode == 404) {
            $actionMaker = Controller::actionGenerator($action, $method, $json);
            $baseControllerResult = Controller::controllerExist('Http', 'base');
            $controllerResult = Controller::controllerExist('Http', $package);
            $actionExist = Controller::actionExist('HttpController', $actionMaker);
            if (!$controllerResult) {
                Router::setPackage('base');
            }

            if (!$controllerResult and !$baseControllerResult) {
                Header::add('HTTP/1.0 404 Not Found', true, 404);
                $this->basedOnHttpCode();
                return false;
            }

            if (!$actionExist) {
                Header::add('HTTP/1.0 404 Not Found', true, 404);
                $this->basedOnHttpCode();
                return false;
            }

            Router::setController('http');
            Router::setAction($action);
        } else {
            $actionMaker = Controller::actionGenerator(Router::getAction(), $method, $json);
            $controllerResult = Controller::controllerExist(Router::getController(), $package);
            $actionExist = Controller::actionExist(Router::getController() . 'Controller', $actionMaker);

            if (!$controllerResult or !$actionExist) {

                $actionMaker = Controller::actionGenerator($action, $method, $json);
                $baseControllerResult = Controller::controllerExist('Http', 'base');
                $controllerResult = Controller::controllerExist('Http', $package);
                $actionExist = Controller::actionExist('HttpController', $actionMaker);

                if (!$controllerResult and !$baseControllerResult) {
                    Header::add('HTTP/1.0 404 Not Found', true, 404);
                    $this->basedOnHttpCode();
                    return false;
                }

                if (!$actionExist) {
                    Header::add('HTTP/1.0 404 Not Found', true, 404);
                    $this->basedOnHttpCode();
                    return false;
                }

                Router::setController('http');
                Router::setAction($action);
            }
        }
    }

    /**
     * Run controller
     * @throws Exception
     */
    public function run()
    {
        $this->basedOnHttpCode();

        $method = Request::getMethod();
        $json = Header::getJson();
        $controllerName = Router::getController();
        $actionName = Router::getAction();
        $arguments = Router::getArguments();
        if (Router::$isRouter and count($arguments) > 1) {
            unset($arguments[0]);
        }
        $action = self::actionGenerator($actionName, $method, $json);

        $result = self::callMethod($controllerName, $action, $arguments);
        if ($result === false) {
            $this->run();
        }
    }
}
