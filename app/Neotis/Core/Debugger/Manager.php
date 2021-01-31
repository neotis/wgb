<?php
/**
 * Generate and display debugger statistics
 * Created by PhpStorm.

 * Date: 11/3/2018
 * Time: 10:52 PM
 * Neotis framework
 */

namespace Neotis\Core\Debugger;

use Neotis\Core\Neotis;

class Manager extends Neotis
{
    /**
     * Display statistics
     */
    public function display()
    {
        $file = 'Files' . DS . 'index.phtml';
        include $file;
    }
}
