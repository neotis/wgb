<?php
/**
 * Event manager class
 * Created by PhpStorm.

 * Date: 10/1/2018
 * Time: 1:01 PM
 * Neotis framework
 */

namespace Neotis\Core\Events;

use Neotis\Core\Neotis;
use Neotis\Core\Services\Methods;


class Manager extends Neotis implements \Neotis\Interfaces\Core\Events\Manager
{
    /**
     * List of core and plugins directories
     * @var array
     */
    public static $directories = [];

    /**
     * List of basic properties for run application
     * @var array
     */
    public static $basicProperties = [];

    /**
     * Fetch directories from plugins and core
     */
    private function fetchDirectories()
    {
        $core = APP_PATH . 'app/Neotis/Core';
        $plugins = APP_PATH . 'app/Neotis/Plugins';

        Methods::folderList($core);
        Methods::folderList($plugins);

        self::$directories = Methods::$listFolders;
    }

    /**
     * Fetch config and json information about require class from core and plugins
     */
    private function fetchConfigs()
    {
        foreach (self::$directories as $key => $value) {
            $file = $value . DIRECTORY_SEPARATOR . 'config.json';
            if (file_exists($file)) {
                $config = file_get_contents($file);
                $config = json_decode($config, true);
                if (isset($config['event'])) {
                    $config = $config['event'];
                    if (!isset($config[0])) {
                        $object = $config['object'];
                        $namesapces = [];
                        if (isset($config['namespaces']) and !empty($config['namespaces'])) {
                            $namesapces = $config['namespaces'];
                        }
                        Jobs::set($config['name'], $object, $config['method'], $config['arguments'], $config['comment'], $namesapces);
                        Jobs::fire($config['position'], $config['name']);
                    } else {
                        foreach ($config as $configKey => $cValue) {
                            $object = $cValue['object'];
                            $namesapces = [];
                            if (isset($cValue['namespaces']) and !empty($cValue['namespaces'])) {
                                $namesapces = $cValue['namespaces'];
                            }
                            Jobs::set($cValue['name'], $object, $cValue['method'], $cValue['arguments'], $cValue['comment'], $namesapces);
                            Jobs::fire($cValue['position'], $cValue['name']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Fetch config and json information about require class from core and plugins
     */
    private function fetchCliConfigs()
    {
        foreach (self::$directories as $key => $value) {
            $file = $value . DIRECTORY_SEPARATOR . 'config.json';
            if (file_exists($file)) {
                $config = file_get_contents($file);
                $config = json_decode($config, true);
                if (isset($config['cli_event'])) {
                    $config = $config['cli_event'];
                    if (!isset($config[0])) {
                        $object = $config['object'];
                        Jobs::set($config['name'], $object, $config['method'], $config['arguments'], $config['comment']);
                        Jobs::fire($config['position'], $config['name']);
                    } else {
                        foreach ($config as $configKey => $cValue) {
                            $object = $cValue['object'];
                            Jobs::set($cValue['name'], $object, $cValue['method'], $cValue['arguments'], $cValue['comment']);
                            Jobs::fire($cValue['position'], $cValue['name']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Fetch config and json information about require class from core and plugins
     */
    private function fetchWatcherConfigs()
    {
        foreach (self::$directories as $key => $value) {
            $file = $value . DIRECTORY_SEPARATOR . 'config.json';
            if (file_exists($file)) {
                $config = file_get_contents($file);
                $config = json_decode($config, true);
                if (isset($config['watcher_event'])) {
                    $config = $config['watcher_event'];
                    if (!isset($config[0])) {
                        $object = $config['object'];
                        Jobs::set($config['name'], $object, $config['method'], $config['arguments'], $config['comment']);
                        Jobs::fire($config['position'], $config['name']);
                    } else {
                        foreach ($config as $configKey => $cValue) {
                            $object = $cValue['object'];
                            Jobs::set($cValue['name'], $object, $cValue['method'], $cValue['arguments'], $cValue['comment']);
                            Jobs::fire($cValue['position'], $cValue['name']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Fetch config and json information about require class from core and plugins
     */
    private function fetchTestConfigs()
    {
        foreach (self::$directories as $key => $value) {
            $file = $value . DIRECTORY_SEPARATOR . 'config.json';
            if (file_exists($file)) {
                $config = file_get_contents($file);
                $config = json_decode($config, true);
                if (isset($config['test_event'])) {
                    $config = $config['test_event'];
                    if (!isset($config[0])) {
                        $object = $config['object'];
                        Jobs::set($config['name'], $object, $config['method'], $config['arguments'], $config['comment']);
                        Jobs::fire($config['position'], $config['name']);
                    } else {
                        foreach ($config as $configKey => $cValue) {
                            $object = $cValue['object'];
                            Jobs::set($cValue['name'], $object, $cValue['method'], $cValue['arguments'], $cValue['comment']);
                            Jobs::fire($cValue['position'], $cValue['name']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Run events manager
     */
    public function run()
    {
        ob_start();
        $this->fetchDirectories();
        $this->fetchConfigs();
        (new Jobs())->prioritize();
        (new ToDo())->_list();
    }

    /**
     * Run cli events
     */
    public function cli()
    {
        /** @var TYPE_NAME $this */
        $this->fetchDirectories();
        $this->fetchCliConfigs();
        (new Jobs())->prioritize();
        (new ToDo())->_list();
    }

    /**
     * Run cli events
     */
    public function watcher()
    {
        /** @var TYPE_NAME $this */
        $this->fetchDirectories();
        $this->fetchWatcherConfigs();
        (new Jobs())->prioritize();
        (new ToDo())->_list();
    }

    /**
     * Run cli events
     */
    public function unitTest()
    {
        /** @var TYPE_NAME $this */
        $this->fetchDirectories();
        $this->fetchTestConfigs();
        (new Jobs())->prioritize();
        (new ToDo())->_list();
    }
}
