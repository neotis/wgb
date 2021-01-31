<?php

/**
 * Send email interface
 * Created by PhpStorm.

 * Date: 5/2/2018
 * Time: 1:23 AM
 * Neotis framework
 */

namespace Neotis\Interfaces\Plugins\Mail;

interface Send
{
    /**
     * Set subject to sent email
     * @param string $string
     * @return mixed
     */
    public function subject($string = '');

    /**
     * Create and set message to send via email functions
     * @param $string
     * @return mixed
     */
    public function message($string);

    /**
     * Send message with email functions
     * @return mixed
     */
    public function send();
}
