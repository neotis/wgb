<?php
/**
 * Session manager and events config
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 12:21 AM
 * Neotis framework
 */

namespace Neotis\Core\Session;

class Manager
{
    /**
     * Run Session manager
     */
    public function run()
    {
        Adapter::start();
    }
}
