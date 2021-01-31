<?php
namespace Neotis\Interfaces\Core\Ini;


/**
 * Extract settings required for different sections from ini file
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 * @router class
 * @Run application
 */
interface Adapter
{
    /**
     * Display ini file as form
     * @param string $file
     * @return array
     */
    public function display($file);

    /**
     * Save array valye to ini file
     * @param string $file
     * @param array $array
     * @return mixed
     */
    public function save($file, $array);
}
