<?php
/**
███╗░░██╗███████╗░█████╗░████████╗██╗░██████╗  ███████╗██████╗░░█████╗░███╗░░░███╗███████╗░██╗░░░░░░░██╗░█████╗░██████╗░██╗░░██╗
████╗░██║██╔════╝██╔══██╗╚══██╔══╝██║██╔════╝  ██╔════╝██╔══██╗██╔══██╗████╗░████║██╔════╝░██║░░██╗░░██║██╔══██╗██╔══██╗██║░██╔╝
██╔██╗██║█████╗░░██║░░██║░░░██║░░░██║╚█████╗░  █████╗░░██████╔╝███████║██╔████╔██║█████╗░░░╚██╗████╗██╔╝██║░░██║██████╔╝█████═╝░
██║╚████║██╔══╝░░██║░░██║░░░██║░░░██║░╚═══██╗  ██╔══╝░░██╔══██╗██╔══██║██║╚██╔╝██║██╔══╝░░░░████╔═████║░██║░░██║██╔══██╗██╔═██╗░
██║░╚███║███████╗╚█████╔╝░░░██║░░░██║██████╔╝  ██║░░░░░██║░░██║██║░░██║██║░╚═╝░██║███████╗░░╚██╔╝░╚██╔╝░╚█████╔╝██║░░██║██║░╚██╗
╚═╝░░╚══╝╚══════╝░╚════╝░░░░╚═╝░░░╚═╝╚═════╝░  ╚═╝░░░░░╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░░░░╚═╝╚══════╝░░░╚═╝░░░╚═╝░░░╚════╝░╚═╝░░╚═╝╚═╝░░╚═╝
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
define('APP_PATH', realpath('.') . DIRECTORY_SEPARATOR);
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once(APP_PATH . 'app/config/config.php');
$app = new Config();

//Run application
$app->unitTest();

//Display debug statistics
//$app->debugger();
