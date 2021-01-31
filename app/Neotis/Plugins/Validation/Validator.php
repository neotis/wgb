<?php
/**
 * Neotis jalali date plugin
 * Created by PhpStorm.
 * User: developer
 * Date: 7/12/2017
 * Time: 2:26 PM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Plugins\Validation;

use Neotis\Core\Http\Request;
use Neotis\Core\Services\Methods;
use Neotis\Plugins\Plugins;

class Validator extends Plugins
{
    /**
     * Check title value and force to < 255 and > 4
     * @param $value
     * @return bool
     */
    public static function title($value)
    {
        if (strlen($value) > 255 or strlen($value) < 4) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check title value and force to < 3999 and > 9
     * @param $value
     * @return bool
     */
    public static function comment($value)
    {
        if (strlen($value) > 4000 or strlen($value) < 10) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check name value and force to < 32 and > 2
     * @param $value
     * @return bool
     */
    public static function name($value)
    {
        if (strlen($value) > 64 or strlen($value) < 3) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check mobile number value
     * @param $value
     * @return bool
     */
    public static function mobile($value)
    {
        if (preg_match('/^[0][9][0-9]{9,9}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check email value
     * @param $value
     * @return bool
     */
    public static function email($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check phone number value
     * @param $value
     * @return bool
     */
    public static function phone($value)
    {
        if (preg_match('/^[0][1-8][0-9]{9,9}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check postal code value
     * @param $value
     * @return bool
     */
    public static function postal($value)
    {
        if (preg_match('/^[0-9]{10,10}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    private static function melliCheck($value)
    {
        if (!preg_match("/^\d{10}$/", $value)
            || $value == '0000000000'
            || $value == '1111111111'
            || $value == '2222222222'
            || $value == '3333333333'
            || $value == '4444444444'
            || $value == '5555555555'
            || $value == '6666666666'
            || $value == '7777777777'
            || $value == '8888888888'
            || $value == '9999999999') {
            return false;
        }
        $check = (int)$value[9];
        $sum = array_sum(array_map(function ($x) use ($value) {
                return ((int)$value[$x]) * (10 - $x);
            }, range(0, 8))) % 11;
        return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
    }

    /**
     * Check melli code value
     * @param $value
     * @return bool
     */
    public static function melli($value)
    {
        if (self::melliCheck($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * one small letter and one numeric field and total 8 chars long
     * @param $value
     * @return bool
     */
    public static function simplePassword($value)
    {
        if (preg_match('/((?=.*[a-z])(?=.*[0-9])(?=.{8,}))/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * One capital, one small letter and one numeric field and total 8 chars long
     * @param $value
     * @return bool
     */
    public static function mediumPassword($value)
    {
        if (preg_match('/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,}))/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check strong password
     * Match the beginning of the string
     * Require that at least one digit appear anywhere in the string
     * Require that at least one lowercase letter appear anywhere in the string
     * Require that at least one uppercase letter appear anywhere in the string
     * Require that at least one special character appear anywhere in the string
     * The password must be at least 8 characters long, but no more than 32
     * Match the end of the string.
     * @param $value
     * @return bool
     */
    public static function strongPassword($value)
    {
        if (preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[*.!@$%^&(){}[]:;<>,.?/~_+-=|\]).{8,32}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if file exist on out host
     * @param $value
     * @return bool
     */
    public static function file($value)
    {
        if (Methods::fileExist($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check username value
     * @param $value
     * @return bool
     */
    public static function username($value)
    {
        if (preg_match('/^(?=[a-zA-Z0-9._]{5,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check username value
     * @param $value
     * @return bool
     */
    public static function number($value)
    {
        if (preg_match('/[0-9]/m', $value)) {
            return true;
        } else {
            return false;
        }
    }
}
