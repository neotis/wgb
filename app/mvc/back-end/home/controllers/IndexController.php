<?php

/**
 * Index controller
 * Created by PhpStorm.
 * Date: 01/01/2021
 * Time: 11:10 AM
 * Neotis framework
 */

use Neotis\Core\Template\Factory as Template;

class IndexController extends ControllerBase
{
    /**
     * First Page of dashboard
     */
    public function indexAction()
    {
        Template::setTitle('Hello World');
    }
}
