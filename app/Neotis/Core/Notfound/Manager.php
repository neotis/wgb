<?php
/**
 * Not found manager
 * Created by PhpStorm.

 * Date: 11/13/2018
 * Time: 12:42 PM
 * Neotis framework
 */

namespace Neotis\Core\Notfound;

use Neotis\Core\Http\Header;
use Neotis\Core\Neotis;
use Neotis\Core\Router\Router;

class Manager extends Neotis
{
    /**
     * Run "not found" application
     */
    public function run()
    {
        if (Router::getNotFound()) {
            Header::add('HTTP/1.0 404 Not Found', true, 404);
        }
    }
}
