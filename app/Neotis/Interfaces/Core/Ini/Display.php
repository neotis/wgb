<?php
/**
 * Created by PhpStorm.

 * Date: 10/23/2017
 * Time: 1:41 PM
 * Neotis framework
 */

namespace Neotis\Interfaces\Core\Ini;


interface Display
{
    /**
     * Read ini file and display as form for edit
     * @param string $file
     * @return array
     */
    public function run($file);
}
