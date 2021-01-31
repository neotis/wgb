<?php
/**
 * Created by PhpStorm.
 * Date: 10/4/2018
 * Time: 12:56 AM
 */

namespace Neotis\Core\Events;


use Neotis\Core\Neotis;

class Jobs extends Neotis implements \Neotis\Interfaces\Core\Events\Jobs
{
    /**
     * List of events
     * @var array
     */
    public static $events = [];

    /**
     * List of event jobs
     * @var array
     */
    public static $jobs = [];

    /**
     * List of events and jobs after prioritize
     * @var array
     */
    public static $prioritize = [];

    /**
     * Store namespaces for replace
     * @var array
     */
    public static $namespaces = [];


    /**
     * Define events job
     * @param $name
     * @param $job
     */
    public static function fire($name, $job)
    {
        self::$events[$name][] = $job;
    }

    /**
     * Define jobs object and event
     * @param $name
     * @param $object
     * @param $method
     * @param array $arguments
     * @param string $comment
     */
    public static function set($name, $object, $method, $arguments = [], $comment = '', $namesapces = [])
    {
        $jobs = self::$jobs;
        if (!isset($jobs[$name])) {
            $jobs[$name] = [
                'name' => $name,
                'object' => $object,
                'method' => $method,
                'arguments' => $arguments,
                'comment' => $comment,
                'namespaces' => $namesapces
            ];
            self::$jobs = $jobs;
        } else {
            //Exception
        }
    }


    /**
     * Define events job and prioritize
     * @param $job
     */
    private function runner($job)
    {
        //Store events
        $events = self::$events;
        //Store jobs event
        $jobs = self::$jobs;
        foreach ($job as $key => $value) {
            if (isset($events['before::' . $value])) {//If `before` event exist before this job
                $this->runner($events['before::' . $value]);
            }

            if (isset($events['replace::' . $value])) {//If `replace` event exist on this job
                $this->runner($events['replace::' . $value]);
            } else {//If `replace` event is not exist on this job
                foreach ($jobs[$value]['namespaces'] as $tKey => $tValue) {
                    self::$namespaces[$tValue['primary']] = $tValue['replace'];
                }
                self::$prioritize[] = $jobs[$value];
            }

            if (isset($events['replace::' . $value . '::' . 1])) {
                $this->runner($events['replace::' . $value . '::' . 1]);
            }


            if (isset($events['after::' . $value])) {//If `after` event exist after this job
                $this->runner($events['after::' . $value]);
            }
        }
    }

    /**
     * Prioritizing events and jobs
     * @return mixed
     */
    public function prioritize()
    {
        $events = self::$events;
        if (isset($events['start'])) {
            $this->runner($events['start']);
        }
        if (isset($events['end'])) {
            $this->runner($events['end']);
        }
    }
}
