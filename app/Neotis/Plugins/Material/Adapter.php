<?php
/**
 * Neotis material plugin
 * Created by PhpStorm.
 * User: developer
 * Date: 7/12/2017
 * Time: 2:26 PM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Plugins\Material;

use Neotis\Plugins\Material\Text\Run as Text;
use Neotis\Plugins\Plugins;

class Adapter extends Plugins
{
    /**
     * Run text field generator and manipulator
     */
    public function text()
    {
        return (new Text());
    }
}
