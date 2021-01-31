<?php
/**
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 8:11 PM
 * Neotis framework
 */

namespace Neotis\Core\Template;


use Neotis\Core\Neotis;

class Manager extends Neotis
{
    /**
     * Define and fetch request headers
     */
    public function run()
    {
        $template = new Factory();
        $template->run();
    }
}
