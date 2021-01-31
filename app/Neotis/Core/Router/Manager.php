<?php
/**
 * Define url information
 * Created by PhpStorm.

 * Date: 10/4/2018
 * Time: 3:27 PM
 * Neotis framework
 */
namespace Neotis\Core\Router;

use Neotis\Core\Neotis;

class Manager extends Neotis
{
    /**
     * Define url object to dispatcher
     */
    public function run()
    {
        $router = new Router();
        $router->run();
    }
}
