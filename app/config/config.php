<?php

/**
 * Neotis configuration
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 */

use Neotis\Core\Debugger\Manager;
use Neotis\Core\Events\Manager as Event;
use Neotis\Core\Exception\Exception;

class Config
{
    /**
     * Store require history
     * @var array
     */
    public static $requireHistory = [];

    /**
     * Attach require file to load application
     * Config constructor.
     */
    public function __construct()
    {
        //attach basic dependencies packages via composer to application
        $dependencies = APP_PATH . 'app/dependencies/vendor/autoload.php';
        if (file_exists($dependencies)) {
            require_once($dependencies);
        } else {
            echo 'Dependencies directory is not exist!';
            die;
        }

        //attach custom dependencies via composer to application
        $modules = APP_PATH . 'app/modules/vendor/autoload.php';
        if (file_exists($modules)) {
            require_once($modules);
        }

        //attach application autoloader for load engine class
        require_once(APP_PATH . 'app/config/loader.php');
    }

    /**
     * Run web application
     */
    public function web()
    {
        try {
            $event = new Event();
            $event->run();
        } catch (Exception $e) {
            echo $e->errorMessage();
            die();
        }
    }

    /**
     * Run web application
     */
    public function watcher()
    {
        try {
            $event = new Event();
            $event->watcher();
        } catch (Exception $e) {
            echo $e->errorMessage();
            die();
        }
    }

    /**
     * Run cli application
     */
    public function cli()
    {
        try {
            echo "
------------------------------------------------------------------
";
            echo "
███╗░░██╗███████╗░█████╗░████████╗██╗░██████╗  ░█████╗░██╗░░░░░██╗
████╗░██║██╔════╝██╔══██╗╚══██╔══╝██║██╔════╝  ██╔══██╗██║░░░░░██║
██╔██╗██║█████╗░░██║░░██║░░░██║░░░██║╚█████╗░  ██║░░╚═╝██║░░░░░██║
██║╚████║██╔══╝░░██║░░██║░░░██║░░░██║░╚═══██╗  ██║░░██╗██║░░░░░██║
██║░╚███║███████╗╚█████╔╝░░░██║░░░██║██████╔╝  ╚█████╔╝███████╗██║
╚═╝░░╚══╝╚══════╝░╚════╝░░░░╚═╝░░░╚═╝╚═════╝░  ░╚════╝░╚══════╝╚═╝
            ";
            echo "
------------------------------------------------------------------
            \n";
            $event = new Event();
            $event->cli();
        } catch (Exception $e) {
            echo $e->errorMessage();
            die();
        }
    }

    /**
     * Display debugger statistics
     */
    public function debugger()
    {
        $debug = new Manager();
        $debug->display();
    }
}
