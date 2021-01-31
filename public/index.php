<?php
/**
 * ███╗░░██╗███████╗░█████╗░████████╗██╗░██████╗  ███████╗██████╗░░█████╗░███╗░░░███╗███████╗░██╗░░░░░░░██╗░█████╗░██████╗░██╗░░██╗
 * ████╗░██║██╔════╝██╔══██╗╚══██╔══╝██║██╔════╝  ██╔════╝██╔══██╗██╔══██╗████╗░████║██╔════╝░██║░░██╗░░██║██╔══██╗██╔══██╗██║░██╔╝
 * ██╔██╗██║█████╗░░██║░░██║░░░██║░░░██║╚█████╗░  █████╗░░██████╔╝███████║██╔████╔██║█████╗░░░╚██╗████╗██╔╝██║░░██║██████╔╝█████═╝░
 * ██║╚████║██╔══╝░░██║░░██║░░░██║░░░██║░╚═══██╗  ██╔══╝░░██╔══██╗██╔══██║██║╚██╔╝██║██╔══╝░░░░████╔═████║░██║░░██║██╔══██╗██╔═██╗░
 * ██║░╚███║███████╗╚█████╔╝░░░██║░░░██║██████╔╝  ██║░░░░░██║░░██║██║░░██║██║░╚═╝░██║███████╗░░╚██╔╝░╚██╔╝░╚█████╔╝██║░░██║██║░╚██╗
 * ╚═╝░░╚══╝╚══════╝░╚════╝░░░░╚═╝░░░╚═╝╚═════╝░  ╚═╝░░░░░╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░░░░╚═╝╚══════╝░░░╚═╝░░░╚═╝░░░╚════╝░╚═╝░░╚═╝╚═╝░░╚═╝
 ***/

/**
 *
 * PHP version 7
 *
 * @category   Framework
 * @package    Neotis
 * @author     Shahin Ataei <shahin.ataei.1990@gmail.com>
 * @copyright  2017 - ~
 * @version    1.0.0.0
 * @link       https://neotis.co/projects/neotis-framework
 */
/* Defined application path as constant*/
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/
define('APP_PATH', realpath('..') . DIRECTORY_SEPARATOR);
DEFINE('DS', DIRECTORY_SEPARATOR);

$whiteListFile = APP_PATH . 'public' . DS . 'whitelist.php';
if (file_exists($whiteListFile)) {
    require_once($whiteListFile);
}

require_once(APP_PATH . 'app/config/config.php');
$app = new Config();

//Run application
$app->web();

//Display debug statistics
//$app->debugger();
