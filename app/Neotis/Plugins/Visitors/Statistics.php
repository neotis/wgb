<?php
/**
 * Statistics visitors log
 * Created by PhpStorm.
 * User: developer
 * Date: 7/12/2017
 * Time: 2:26 PM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Plugins\Visitors;

use Neotis\Plugins\Plugins;

class Statistics extends Plugins
{
    public static $days = [];
    public static $today = 0;
    public static $thisMonth = 0;
    public static $month = [];

    private static $dayNames = [
        '0' => 'شنبه',
        '1' => 'یکشنبه',
        '2' => 'دوشنبه',
        '3' => 'سه شنبه',
        '4' => 'چهار شنبه',
        '5' => 'پنجشنبه',
        '6' => 'جمعه'
    ];

    private static $monthNames = [
        '1' => 'فروردین',
        '2' => 'اردیبهشت',
        '3' => 'خرداد',
        '4' => 'تیر',
        '5' => 'مرداد',
        '6' => 'شهریور',
        '7' => 'مهر',
        '8' => 'آبان',
        '9' => 'آذر',
        '10' => 'دی',
        '11' => 'بهمن',
        '12' => 'اسفند'
    ];

    /**
     * Calculate visit count of selected day by timestamp
     * @param $visit
     * @param $day
     * @param string $type
     */
    public static function byDay($visit, $day, $type = 'normal')
    {
        if ($visit['timestamp'] >= $day['timestamp'] and $visit['timestamp'] <= ($day['timestamp'] + 86400)) {
            if (isset(self::$days[$day['dayOfWeek']])) {
                self::$days[$day['dayOfWeek']]['count']++;
                if($type == 'today'){
                    self::$today++;
                }
            }else{
                if($type == 'today'){
                    $name = 'امروز';
                    self::$today++;
                }else{
                    $name = self::$dayNames[$day['dayOfWeek']];
                }
                self::$days[$day['dayOfWeek']] = [
                    'name' => $name,
                    'count' => 1
                ];
            }
        }
    }

    /**
     * Calculate visit count of selected month by timestamp
     * @param $visit
     * @param $month
     * @param $endMonth
     * @param string $type
     */
    public static function byMonth($visit, $month, $endMonth, $type = 'normal')
    {
        if ($visit['timestamp'] >= $month['timestamp'] and $visit['timestamp'] <= $endMonth['timestamp']) {
            if (isset(self::$month[$month['month']])) {
                self::$month[$month['month']]['count']++;
                if($type == 'thisMonth'){
                    self::$thisMonth++;
                }
            }else{
                if($type == 'thisMonth'){
                    $name = 'این ماه';
                    self::$thisMonth++;
                }else{
                    $name = self::$monthNames[$month['month']];
                }
                self::$month[$month['month']] = [
                    'name' => $name,
                    'count' => 1
                ];
            }
        }
    }
}
