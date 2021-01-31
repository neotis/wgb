<?php
/**
 * Script tags manager
 * Created by PhpStorm.
 * Date: 11/22/2018
 * Time: 11:54 AM
 */

namespace Neotis\Core\Template\Js;

use Neotis\Core\Exception\Exception;
use MatthiasMullie\Minify;
use Neotis\Core\Http\Header;
use Neotis\Core\Mvc\View;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

trait Manipulator
{
    /**
     * Store java script base file
     * @var string
     */
    private $jsBase = '';

    /**
     * Store java script files path to combine
     * @var array
     */
    private static $topJsFiles = [];

    /**
     * Store java script initial files path to combine
     * @var array
     */
    private static $initialJsFiles = [];

    /**
     * Store css links
     * @var array
     */
    private static $topJsLinks = [];

    /**
     * Store java script files path to combine
     * @var array
     */
    private static $bottomJsFiles = [];

    /**
     * Store css links
     * @var array
     */
    private static $bottomJsLinks = [];


    /**
     * Manipulate single javas script file
     * @param $key
     * @return mixed
     */
    private function manipulateJs($key)
    {
        if (strpos($key, "bottom.index.js")) {
            $searchIn = 'bottom.index.js';
            $key = str_ireplace($searchIn, '', $key);
        } elseif (strpos($key, "index.js")) {
            $searchIn = 'index.js';
            $key = str_ireplace($searchIn, '', $key);
        } elseif (strpos($key, "initial.js")) {
            $searchIn = 'initial.js';
            $key = str_ireplace($searchIn, '', $key);
        }
        $tag = '';
        $component = explode('components', $key);
        $controller = explode('controllers', $key);
        $mainer = explode(View::getDevice() . DS . 'main', $key);
        $mainStatus = false;
        if (isset($component[1])) {
            $explode = 'neo' . DS . 'component' . $component[1];
            $explode = explode(DS, $explode);
            $tag = implode('-', $explode);
        } elseif (isset($controller[1])) {
            $explode = 'neo' . $controller[1];
            $explode = explode(DS, $explode);
            $tag = implode('-', $explode);
        } elseif (isset($mainer[1])) {
            $mainStatus = true;
            $explode = Router::getPackage() . $mainer[1];
            $explode = explode(DS, $explode);
            $tag = implode('-', $explode);
        }
        $tag = rtrim($tag, "-");

        $public = APP_PATH . 'public';
        $base = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end';
        $final = str_ireplace($base, $public, $key);

        $main = Router::getPackage() . '/desktop/main/' . View::getMainIndex() . '/assets/';


        //Check and replace js files
        $content = file_get_contents($key . $searchIn);
        $search = [
            '"neo_assets/',
            "'neo_assets/",
            "neo_assets/",
            '"neo_main/',
            "'neo_main/",
            "neo_main/"
        ];
        $replace = [
            '"' . $final . 'assets/',
            '"' . $final . 'assets/',
            '"' . $final . 'assets/',
            '"' . $main,
            '"' . $main,
            '"' . $main
        ];
        $result = str_ireplace($search, $replace, $content);
        $result = str_ireplace(APP_PATH, '/', $result);
        $result = str_ireplace('\\', '/', $result);

        if ($searchIn != 'initial.js') {
            $function = str_ireplace('-', '_', $tag);
            $function = 'caller_' . $function;
            if ($mainStatus) {
                $ajaxLoad = "$(document).ajaxComplete(function() { if ($('body[type=" . $tag . "]').length && ajaxCompleteStatus == 'page') {" . $result . "} if((typeof var_$function) === 'undefined' && (typeof $function) !== 'undefined'){var_" . $function . " = true;" . $function . "();} });";
                $ajaxLoad .= "$( document ).ready(function() { if ($('body[type=" . $tag . "]').length) {" . $result . "}  if((typeof var_$function) === 'undefined' && (typeof $function) !== 'undefined'){var_" . $function . " = true;" . $function . "();} });";
            } else {
                $ajaxLoad = "$(document).ajaxComplete(function() { if ($('" . $tag . "').length && ajaxCompleteStatus == 'page') {" . $result . "} if((typeof var_$function) === 'undefined' && (typeof $function) !== 'undefined'){var_" . $function . " = true;" . $function . "();}  });";
                $ajaxLoad .= "$( document ).ready(function() { if ($('" . $tag . "').length) {" . $result . "}  if((typeof var_$function) === 'undefined' && (typeof $function) !== 'undefined'){var_" . $function . " = true;" . $function . "();} });";
            }
            $ajaxLoad = str_ireplace('onceload()', $function . '()', $ajaxLoad);
            return $ajaxLoad;
        } else {
            return $result;
        }
    }

    /**
     * Fetch java script files from selected package
     */
    private function fetchJsFiles()
    {
        $history = [];
        Methods::$listFolders = [];
        $basePackage = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $this->package;
        Methods::folderList($basePackage);
        foreach (Methods::$listFolders as $key => $value) {
            $compare = str_ireplace(APP_PATH, '', $value);
            $compare = explode(DS, $compare);
            $js = $value . DS . 'initial.js';
            if (file_exists($js) and (count($compare) === 6 || count($compare) === 7 || count($compare) === 8)) {
                $history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $this->package, '', $value)] = true;
                $this->setInitialJs($js);
            }
            $js = $value . DS . 'index.js';
            if (file_exists($js) and (count($compare) === 6 || count($compare) === 7 || count($compare) === 8)) {
                $history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $this->package, '', $value)] = true;
                $this->setTopJs($js);
            }
            $js = $value . DS . 'bottom.index.js';
            if (file_exists($js) and (count($compare) === 6 || count($compare) === 7 || count($compare) === 8)) {
                $history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $this->package, '', $value)] = true;
                $this->setBottomJs($js);
            }
        }

        Methods::$listFolders = [];
        $basePackage = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base';
        Methods::folderList($basePackage);
        foreach (Methods::$listFolders as $key => $value) {
            $compare = str_ireplace(APP_PATH, '', $value);
            $compare = explode(DS, $compare);
            $js = $value . DS . 'initial.js';
            if (file_exists($js) and (count($compare) === 6 || count($compare) === 7 || count($compare) === 8)) {
                if(!isset($history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base', '', $value)])){
                    $this->setInitialJs($js);
                }
            }
            $js = $value . DS . 'index.js';
            if (file_exists($js) and (count($compare) === 6 || count($compare) === 7 || count($compare) === 8)) {
                if(!isset($history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base', '', $value)])) {
                    $this->setTopJs($js);
                }
            }
            $js = $value . DS . 'bottom.index.js';
            if (file_exists($js) and (count($compare) === 6 || count($compare) === 7 || count($compare) === 8)) {
                if(!isset($history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base', '', $value)])) {
                    $this->setBottomJs($js);
                }
            }
        }
    }

    /**
     * Fetch java script base file details
     * @throws Exception
     */
    private function jsGenerator()
    {
        $this->fetchJsFiles();
        $device = View::getDevice();
        $file = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Template' . DS . 'Files' . DS . 'base.js';
        if (file_exists($file)) {
            $minifier = new Minify\JS($file);
            foreach (self::$initialJsFiles as $key => $value) {
                if (file_exists($key)) {
                    $minifier->add($this->manipulateJs($key));
                }
            }
            foreach (self::$topJsFiles as $key => $value) {
                if (file_exists($key)) {
                    $minifier->add($this->manipulateJs($key));
                }
            }
            $base = APP_PATH . 'public' . DS . $this->package . DS . $device . DS . 'base' . DS . 'js' . DS . 'base.js';
            if (file_exists($base)) {
                $minifier->minify($base);
            } else {
                $pathInfo = pathinfo($base);
                @mkdir($pathInfo['dirname'], 755, true);
                touch($base);
                $minifier->minify($base);
            }
            $this->addTopJs('/' . $this->package . '/' . $device . '/base/js/base.js');

            $minifier = new Minify\JS();
            foreach (self::$bottomJsFiles as $key => $value) {
                if (file_exists($key)) {
                    $minifier->add($this->manipulateJs($key));
                }
            }
            $base = APP_PATH . 'public' . DS . $this->package . DS . $device . DS . 'base' . DS . 'js' . DS . 'bottom.base.js';
            if (file_exists($base)) {
                $minifier->minify($base);
            } else {
                $pathInfo = pathinfo($base);
                @mkdir($pathInfo['dirname'], 755, true);
                touch($base);
                $minifier->minify($base);
            }
            $this->addBottomJs('/' . $this->package . '/' . $device . '/base/js/bottom.base.js');
        } else {
            throw new Exception('<b>base.css</b> is not exist on template factory files');
        }
    }

    /**
     * Define css path files and store on $files
     * @param $file
     */
    public static function setTopJs($file)
    {
        self::$topJsFiles[$file] = true;
    }

    /**
     * Define javascript initial path files and store on $files
     * @param $file
     */
    public static function setInitialJs($file)
    {
        self::$initialJsFiles[$file] = true;
    }

    /**
     * Add css file to template
     * @param $file
     */
    public static function addTopJs($file)
    {
        self::$topJsLinks[$file] = true;
    }


    /**
     * Return css links array
     */
    public static function getTopJs()
    {
        return self::$topJsLinks;
    }

    /**
     * Define css path files and store on $files
     * @param $file
     */
    public static function setBottomJs($file)
    {
        self::$bottomJsFiles[$file] = true;
    }

    /**
     * Add css file to template
     * @param $file
     */
    public static function addBottomJs($file)
    {
        self::$bottomJsLinks[$file] = true;
    }


    /**
     * Return css links array
     */
    public static function getBottomJs()
    {
        return self::$bottomJsLinks;
    }

    /**
     * Return top script tags for attached in template factory
     * @return string
     */
    public static function getTopScriptString()
    {
        $js = '';
        $base = '';
        foreach (self::getTopJs() as $key => $value) {
            $split = explode('/', $key);
            $key .= '?version=' . self::$version;
            if (end($split) !== 'base.js') {
                $js .= '<script src="' . $key . '"></script>';
            } else {
                $base = '<script src="' . $key . '"></script>';
            }

        }
        return $js . $base;
    }

    /**
     * Return bottom script tags for attached in template factory
     * @return string
     */
    public static function getBottomScriptString()
    {
        $js = '';
        $base = '';
        foreach (self::getBottomJs() as $key => $value) {
            $key .= '?version=' . self::$version;
            $split = explode('/', $key);
            if (end($split) !== 'bottom.base.js') {
                $js .= '<script src="' . $key . '"></script>';
            } else {
                $base = '<script src="' . $key . '"></script>';
            }

        }
        return $js . $base;
    }

    /**
     * Get style from component and action
     * @throws Exception
     */
    private function runScript()
    {
        $this->jsGenerator();
    }
}
