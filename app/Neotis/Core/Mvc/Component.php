<?php
/**
 * Component manager
 * Created by PhpStorm.

 * Date: 10/22/2018
 * Time: 3:52 PM
 * Neotis framework
 */

namespace Neotis\Core\Mvc;

use Neotis\Core\Neotis;

class Component extends Neotis
{
    /**
     * Component variables
     * @var array
     */
    public static $vars = [];

    /**
     * Component manipulated template variables
     * @var array
     */
    public static $tempVars = [];

    public function caller($controller, $component)
    {

    }
}
