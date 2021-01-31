<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 10/23/2017
 * Time: 1:41 PM
 */

namespace Neotis\Core\Ini;

use Neotis\Core\Neotis;
use Matomo\Ini\IniReader;

class Display extends Neotis implements \Neotis\Interfaces\Core\Ini\Display
{
    /**
     * Store ini information
     * @var array
     */
    public static $settings = [];

    /**
     * Read ini file and display as form for edit
     * @param string $file
     * @return array
     * @throws \Matomo\Ini\IniReadingException
     */
    public function run($file = '')
    {
        $reader = new IniReader();
        $array = $reader->readFile($file);
        return $array;
    }
}