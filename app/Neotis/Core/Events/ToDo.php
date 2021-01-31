<?php
/**
 * Tun list of todo list
 * Created by PhpStorm.

 * Date: 10/4/2018
 * Time: 12:56 AM
 * Neotis framework
 */

namespace Neotis\Core\Events;


use Neotis\Core\Neotis;

class ToDo extends Neotis implements \Neotis\Interfaces\Core\Events\ToDo
{
    /**
     * Run object and methods from events and jobs
     * @param $object
     * @param $method
     * @param array $arguments
     */
    private function caller($object, $method, $arguments = [])
    {
        $object = new $object();
        call_user_func_array(array($object, $method), $arguments);
    }

    /**
     * Run list of todo jobs and events
     */
    public function _list()
    {
        foreach (Jobs::$prioritize as $key => $value) {
            $this->caller($value['object'], $value['method'], $value['arguments']);
        }
    }

}
