<?php
/**
 * Custom exception
 * Created by PhpStorm.

 * Date: 11/16/2018
 * Time: 6:21 PM
 * Neotis framework
 */

namespace Neotis\Core\Exception;

use Exception as ParentException;

class Exception extends ParentException
{
    /**
     * Display custom error to user
     * @return string
     */
    public function errorMessage()
    {
        $message = [];
        $message['code'] = $this->getCode();
        if($message['code'] === 1){
            $message['result'] = true;
        }else{
            $message['result'] = false;
        }
        $message['message'] = $this->getMessage();
        $message['file'] = $this->getFile();
        $message['line'] = $this->getLine();
        $errorMsg = json_encode($message, JSON_UNESCAPED_UNICODE );
        return $errorMsg;
    }

    /**
     * Create custom final message to user
     */
    public function customMessage()
    {

    }
}
