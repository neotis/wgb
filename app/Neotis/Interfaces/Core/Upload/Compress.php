<?php
namespace Neotis\Interfaces\Core\Upload;

/**
 * Compress uploaded file class interface
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 9:15 PM
 * Neotis framework
 */

interface Compress
{
    /**
     * Return query from url address
     * @param $source
     * @param $destination
     * @return string
     */
    public function uploaded($source, $destination);
}
