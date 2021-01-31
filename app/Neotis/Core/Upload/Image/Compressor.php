<?php
/**
 * Compress uploaded images
 * Created by PhpStorm.

 * Date: 05/07/2020
 * Time: 11:49 AM
 * Neotis framework
 */

namespace Neotis\Core\Upload\Image;

use Neotis\Core\Services\Methods;
use Neotis\Core\Upload\Upload;
use Neotis\Interfaces\Core\Upload\Compress;
use Kinglozzer\TinyPng\Compressor as tinyPngCompress;
use Kinglozzer\TinyPng\Exception\AuthorizationException;
use Kinglozzer\TinyPng\Exception\InputException;
use Kinglozzer\TinyPng\Exception\LogicException;

class Compressor extends Upload implements Compress
{
    public static $apiKey  = '';

    public function uploaded($source, $destination)
    {
        $compressor = new tinyPngCompress(self::$apiKey);
        $result = $compressor->compress($source);
        try {
            $result->writeTo($destination);
        } catch (\Exception $e) {

        } // Write the returned image
        $response = $result->getResponseData(); // array containing JSON-decoded response data
    }
}
