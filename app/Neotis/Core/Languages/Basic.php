<?php

namespace Neotis\Core\Languages;

use Neotis\Core\Neotis;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

/**
 * Created by PhpStorm.
 * User: developer
 * Date: 10/7/2018
 * Time: 12:18 PM
 */
class Basic extends Neotis
{
    /**
     * Store language message
     * @var array
     */
    private $messages = [];

    /**
     * Manage selected language and fetch language data about defined class
     * @param string $path
     */
    public function __construct($path = '')
    {
        $packageConfig = Router::getPackagesConfigs();
        $package = Router::getPackage();
        $packageConfig = $packageConfig['packages'][$package];
        if (isset($packageConfig['language'])) {
            $language = $packageConfig['language'];
        } else {
            $language = 'english';
        }

        $languageIso = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Languages' . DS . 'config.json';
        $languageIso = file_get_contents($languageIso);
        $languageIso = Methods::jsonIn($languageIso);

        $basePath = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Languages' . DS;

        if (isset($languageIso['iso'][$language])) {

            if (file_exists($basePath . $path . DS . $languageIso['iso'][$language] . '.json')) {

                $file = file_get_contents($basePath . $path . DS . $languageIso['iso'][$language] . '.json');
                $this->messages = Methods::jsonIn($file);

            } elseif (file_exists($basePath . $path . DS . 'en.json')) {

                $this->messages = Methods::jsonIn($basePath . $path . DS . 'en.json');

            } else {
                $this->messages = [];
            }

        } else {

            if (file_exists($basePath . $path . 'en.json')) {

                $file = file_get_contents($basePath . $path . $languageIso['iso'][$language] . 'json');
                $this->messages = Methods::jsonIn($file);

            } else {

                $this->messages = [];

            }
        }
    }

    /**
     * Get message from selected package ans class
     * @param $index
     * @return mixed|string
     */
    public function get($index)
    {
        if (isset($this->messages[$index])) {
            return $this->messages[$index];
        } else {
            return '';
        }
    }
}
