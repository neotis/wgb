<?php

/**
 * Send sms api interface
 * Created by PhpStorm.

 * Date: 4/27/2018
 * Time: 7:42 PM
 * Neotis framework
 */

namespace Neotis\Interfaces\Plugins\Sms;

interface Send
{
    /**
     * Send sms for single number
     * @param $receptor
     * @param $message
     * @param $output
     * @param $sendDate
     * @param $checkMessageIds
     * @return mixed
     */
    public function sendSingle($receptor, $message, $output, $sendDate, $checkMessageIds);

    /**
     * Send sms for group numbers with singe sender
     * @param $receptor
     * @param $message
     * @param $output
     * @param $groupIds
     * @param $sendDate
     * @param $checkMessageIds
     * @return mixed
     */
    public function sendGroup($receptor, $message, $groupIds, $output, $sendDate, $checkMessageIds);

    /**
     * Send sms for group numbers with multiple sender
     * @param $receptor
     * @param $message
     * @param $senders
     * @param $groupIds
     * @param $output
     * @param $sendDate
     * @param $checkMessageIds
     * @return mixed
     */
    public function sendGroupTwo($receptor, $message, $senders, $groupIds, $output, $sendDate, $checkMessageIds);

}
