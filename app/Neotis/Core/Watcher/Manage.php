<?php

/**
 * Created by PhpStorm.

 * Date: 11/14/2017
 * Time: 3:41 PM
 * Neotis framework
 */

namespace Neotis\Core\Watcher;

use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Core\Mvc\View;
use Neotis\Core\Neotis;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;
use Neotis\Core\Template\Manager;
use Neotis\Core\Cache\View as CacheView;
use Neotis\Core\Mvc\Manager as MvcManager;

class Manage extends Neotis
{
    public static $watcher = false;

    /**
     * Make request for generate new html cache
     * @param string $type
     * @param $package
     * @param $controller
     * @param $action
     * @throws \Neotis\Core\Exception\Exception
     */
    private function request($type = 'normal', $package, $controller, $action, $method)
    {
        echo $package . ' = ' . $controller . ' = ' . $action . PHP_EOL;
        if ($type == 'normal') {
            Header::$pure = false;
            Header::$partial = false;
            Header::$component = false;
        } elseif ($type == 'component') {
            Header::$pure = false;
            Header::$partial = false;
            Header::$component = true;
        } elseif ($type == 'pure-partial') {
            Header::$pure = true;
            Header::$partial = true;
            Header::$component = true;
        } elseif ($type == 'partial') {
            Header::$pure = false;
            Header::$partial = true;
            Header::$component = true;
        }
        $view = new View();
        $template = new Manager();
        $mvc = new MvcManager();

        Request::setMethod($method);
        Router::setController($controller);
        Router::setAction($action);
        Router::setPackage($package);
        CacheView::cacheChecker();

        $mvc->view();
        $template->run();
        $view->display();
    }

    private function doWhile($pages, $type = 'normal')
    {
        if (!empty($pages)) {
            $key = array_key_first($pages);
            $page = $pages[$key];
            unset($pages[$key]);
            if ($page['type'] == 'controller') {
                $this->request($type, $page['package'], $page['controller'], $page['action'], 'get');
            }
            $this->doWhile($pages);
        }
    }


    private function pages()
    {
        $pages = \AccessesPages::connect()->where([
            'display' => 'html'
        ])->findAll();
        return $pages;
    }

    private function historyChecker($list)
    {
        $oldFile = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Watcher' . DS . 'Histories' . DS . 'old.json';
        $old = file_get_contents($oldFile);
        $old = json_decode($old, true);
        $listMaker = [];
        $final = [];
        foreach ($list as $key => $value) {
            $currentSize = Methods::folderSize($key);
            if ($currentSize != $old[$key]) {
                $final[$key] = $value;
            }
            $listMaker[$key] = $currentSize;
        }
        file_put_contents($oldFile, json_encode($listMaker));
        return $final;
    }

    private function changedPages()
    {
        $front = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end';
        Methods::$listFolders = [];
        Methods::folderList($front);
        $list = [];
        foreach (Methods::$listFolders as $key => $value) {
            $path = str_ireplace($front, '', $value);
            $pathArray = explode(DS, $path);
            if (isset($pathArray[5])) {
                $base = $pathArray[0] . DS . $pathArray[1] . DS . $pathArray[2] . DS . $pathArray[3];
                $file = $front . $base . DS . $pathArray[4] . DS . $pathArray[5] . DS . 'index.phtml';
                if (!isset($list[$file])) {
                    if (is_file($file) and $pathArray[3] == 'controllers') {
                        $list[$file] = [
                            'package' => $pathArray[1],
                            'controller' => $pathArray[4],
                            'action' => $pathArray[5],
                            'type' => 'controller'
                        ];
                    }
                    if (is_file($file) and $pathArray[3] == 'components') {
                        $list[$file] = [
                            'package' => $pathArray[1],
                            'type' => 'component',
                            'tag' => $pathArray[4] . '-' . $pathArray[5]
                        ];
                    }
                }
            } elseif (isset($pathArray[4])) {
                $base = $pathArray[0] . DS . $pathArray[1] . DS . $pathArray[2] . DS . $pathArray[3];
                $file = $front . $base . DS . $pathArray[4] . DS . 'index.phtml';
                if (!isset($list[$file])) {
                    if (is_file($file) and $pathArray[3] == 'components') {
                        $list[$file] = [
                            'package' => $pathArray[1],
                            'type' => 'component',
                            'tag' => $pathArray[4]
                        ];
                    }
                }
            }
        }
        return $this->historyChecker($list);
    }

    /**
     * Generate html and content from "Neotis" framework
     * @throws \Neotis\Core\Exception\Exception
     */
    private function html()
    {
        $pages = $this->changedPages();

        $this->doWhile($pages);
        $this->doWhile($pages, 'pure-partial');
        $this->doWhile($pages, 'partial');
    }

    /**
     * Start "Neotis" file watcher
     */
    public function start()
    {
        self::$watcher = true;
        Header::$httpCode = 200;
        Request::$watcher = true;

        $router = new Router();

        $router->run();

        $this->html();
        sleep(1);
        $this->start();
    }
}
