<?php
/**
 * Interface of save data as ini config
 * Created by PhpStorm.

 * Date: 10/23/2017
 * Time: 5:50 PM
 * Neotis framework
 */

namespace Neotis\Interfaces\Core\Ini;


interface Save
{
    /**
     * Insert array value as ini to file
     * @param string $file
     * @param array $array
     */
    public function run($file, $array);
}
