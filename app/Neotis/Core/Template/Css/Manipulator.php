<?php
/**
 * Produce view styles
 * Created by PhpStorm.

 * Date: 11/17/2018
 * Time: 8:41 PM
 * Neotis framework
 */

namespace Neotis\Core\Template\Css;

use Neotis\Core\Exception\Exception;
use MatthiasMullie\Minify;
use Neotis\Core\Mvc\View;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

trait Manipulator
{
    /**
     * Store css base file
     * @var string
     */
    private $base = '';

    /**
     * Store css files path to combine
     * @var array
     */
    private static $files = [];

    /**
     * Store css links
     * @var array
     */
    private static $links = [];

    /**
     * Manipulate single css file
     * @param $key
     * @return mixed
     */
    private function manipulateCss($key)
    {
        $search = 'index.css';

        $key = str_ireplace($search, '', $key);

        //Copy directories assets
        $public = APP_PATH . 'public';
        $base = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end';

        $main = Router::getPackage() . '/desktop/main/' . View::getMainIndex() . '/assets/';

        $final = str_ireplace($base, $public, $key);

        if (is_dir($key . 'neo_assets')) {
            $currentSize = Methods::folderSize($key . 'neo_assets');
            $destSize = Methods::folderSize($final . 'assets');
            if ($currentSize !== $destSize and (($currentSize - 4096 !== $destSize))) {
                Methods::rmDir($final . 'assets');
                Methods::copyDirectory($key . 'neo_assets', $final . 'assets');
            }
        }

        //Check and replace css files
        $content = file_get_contents($key . 'index.css');
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
        return $result;
    }

    /**
     * Attache css file to factory templates
     * @return string
     */
    public static function getCssString()
    {
        $css = '';
        $base = '';
        foreach (self::getCss() as $key => $value) {
            $split = explode('/', $key);
            $key .= '?version=' . self::$version;
            if (end($split) !== 'base.css') {
                $css .= '<link rel="stylesheet" type="text/css" href="' . $key . '">';
            } else {
                $base = '<link rel="stylesheet" type="text/css" href="' . $key . '">';
            }
        }
        return $css . $base;
    }

    /**
     * Find the cs files from the selected folder of package
     * Then attache to template file as CSS link
     * Attache base packages css files to manipulated html
     */
    private function fetchFiles()
    {
        /**
         * Selected package
         */
        $history = [];
        Methods::$listFolders = [];
        $basePackage = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $this->package;
        Methods::folderList($basePackage);
        foreach (Methods::$listFolders as $key => $value) {
            $css = $value . DS . 'index.css';
            $history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . $this->package, '', $value)] = true;
            if (file_exists($css)) {
                $this->setCss($css);
            }
        }

        /**
         * Base packages
         */
        Methods::$listFolders = [];
        $basePackage = APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base';
        Methods::folderList($basePackage);
        foreach (Methods::$listFolders as $key => $value) {
            $css = $value . DS . 'index.css';
            if (file_exists($css)) {
                if(!isset($history[str_ireplace(APP_PATH . 'app' . DS . 'mvc' . DS . 'front-end' . DS . 'base', '', $value)])) {
                    $this->setCss($css);
                }
            }
        }
    }

    /**
     * Fetch css base file details
     * Generate base css file and minify it
     * @throws Exception
     */
    private function cssGenerator()
    {
        $device = View::getDevice();
        $file = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Template' . DS . 'Files' . DS . 'base.css';

        if (file_exists($file)) {

            $minifier = new Minify\CSS($file);

            foreach (self::$files as $key => $value) {
                if (file_exists($key)) {
                    $minifier->add($this->manipulateCss($key));
                }
            }

            $base = APP_PATH . 'public' . DS . $this->package . DS . $device . DS . 'base' . DS . 'css' . DS . 'base.css';
            if (file_exists($base)) {
                $minifier->minify($base);
            } else {
                $pathInfo = pathinfo($base);
                @mkdir($pathInfo['dirname'], 755, true);
                touch($base);
                $minifier->minify($base);
            }

            $this->add('/' . $this->package . '/' . $device . '/base/css/base.css');
        } else {
            throw new Exception('<b>base.css</b> is not exist on template factory files');
        }
    }

    /**
     * Define css path files and store on $files
     * @param $file
     */
    public static function setCss($file)
    {
        self::$files[$file] = true;
    }

    /**
     * Add css file to template
     * @param $file
     */
    public static function add($file)
    {
        self::$links[$file] = true;
    }


    /**
     * Return css links array
     */
    public static function getCss()
    {
        return self::$links;
    }

    /**
     * Get style from component and action
     * @throws Exception
     */
    private function run()
    {
        $this->fetchFiles();//Fetch files from selected package and base package
        $this->cssGenerator();//Generate base.css file
    }
}
