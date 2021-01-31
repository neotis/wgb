<?php
/**
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 8:11 PM
 * Neotis framework
 */

namespace Neotis\Core\Mvc;


use Neotis\Core\Neotis;

class Manager extends Neotis
{
    /**
     * Define controller manager
     * @throws \Neotis\Core\Exception\Exception
     */
    public function controller()
    {
        $controller = new Controller();
        $controller->run();
    }

    /**
     * Define view manager
     */
    public function view()
    {
        $view = new View();
        $view->run();
    }
}
