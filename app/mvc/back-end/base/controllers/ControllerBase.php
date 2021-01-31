<?php
/**
 * Base controller
 * Created by PhpStorm.

 * Date: 11/17/2017
 * Time: 1:17 PM
 * Neotis framework
 */

use Neotis\Core\Mvc\Controller;
use Neotis\Core\Template\Factory as Template;

class ControllerBase extends Controller
{
    /**
     * ControllerBase constructor.
     */
    public function __construct()
    {
        Template::setBaseTitle('Neotis Projects');
    }
}
