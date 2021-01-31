<?php
/**
 * Register visitors log for statistics
 * Created by PhpStorm.
 * User: developer
 * Date: 7/12/2017
 * Time: 2:26 PM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Plugins\Visitors;

use Neotis\Api\Users\Users;
use Neotis\Core\Http\Request;
use Neotis\Core\Router\Router;
use Neotis\Plugins\Jalali\Date;
use Neotis\Plugins\Plugins;

class Register extends Plugins
{
    public function detectOnline()
    {
        $users = \StatisticsVisitors::connect()->where([
            'status' => 1
        ])->findAll();

        foreach ($users as $key => $value) {
            if ((time() - $value['modify']) > 120 and $value['modify'] != '0') {
                \StatisticsVisitors::connect()->where($value['id'])->values([
                    'status' => 0
                ])->update();
            }
        }
    }


    public static function start()
    {
        $today = Date::persianDate(time(), 'details');

        $visitor = \StatisticsVisitors::connect()->where([
            'ip' => Request::getUserIp()
        ])->order('id DESC')->find();
        if (!$visitor) {
            \StatisticsVisitors::connect()->values([
                'year' => $today['year'],
                'month' => $today['month'],
                'day' => $today['day'],
                'time' => $today['hour'],
            ])->add();
        } else {
            if (((time() - $visitor['modify']) > 7200) and ($visitor['modify'] != '0') or ($visitor['modify'] == '0' and (time() - $visitor['timestamp']) > 7200)) {
                \StatisticsVisitors::connect()->values([
                    'year' => $today['year'],
                    'month' => $today['month'],
                    'day' => $today['day'],
                    'time' => $today['hour'],
                    'status' => 1
                ])->add();
            }else{
                \StatisticsVisitors::connect()->where([
                    'ip' => Request::getUserIp(),
                    'year' => $today['year'],
                    'month' => $today['month'],
                    'day' => $today['day']
                ])->values([
                    'year' => $today['year'],
                    'month' => $today['month'],
                    'day' => $today['day'],
                    'time' => $today['hour'],
                    'status' => 1
                ])->update();
            }
        }
    }

    public static function online()
    {
        $users = \StatisticsVisitors::connect()->where([
            'status' => 1
        ])->findAll();
        if(!$users){
            $users = [];
        }
        return count($users);
    }
}
