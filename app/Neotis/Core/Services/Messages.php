<?php
/**
 * Messages generator
 * Created by PhpStorm.

 * Date: 8/13/2017
 * Time: 11:42 AM
 * Neotis framework
 */

namespace Neotis\Core\Services;

use Neotis\Core\Http\Request;
use Neotis\Core\Neotis;

class Messages extends Neotis
{
    /**
     * Create json array base on fetch array
     * @param int $code
     * @param bool $result
     * @param string $message
     * @param array $details
     * @return array
     */
    public static function jsonArray($code = 0, $result = false, $message = '', $details = [])
    {
        $array = [];

        $array['code'] = $code;
        $array['result'] = $result;
        $array['message'] = $message;
        $array['details'] = $details;

        return $array;
    }
}
