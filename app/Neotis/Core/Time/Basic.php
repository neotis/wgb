<?php
namespace Neotis\Core\Time;

use \Neotis\Core\Neotis;
use Neotis\Core\Ini\Display;

/**
 * Basic settings of date and time
 * Created by PhpStorm.

 * Date: 10/7/2018
 * Time: 11:49 AM
 * Neotis framework
 */

class Basic extends Neotis
{
    /**
     * Define basic settings
     */
    public function run()
    {
        date_default_timezone_set(Display::$settings['default']['timeZone']);
    }
}
