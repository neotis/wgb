<?php
/**
 * Created by PhpStorm.

 * Date: 8/13/2017
 * Time: 11:42 AM
 * Neotis framework
 */

namespace Neotis\Core\Services;

use JetBrains\PhpStorm\Pure;
use Neotis\Core\Http\Request;
use Neotis\Core\Neotis;

class Methods extends Neotis
{
    /**
     * Store list of folders and subfolders
     * @var array
     */
    public static $listFolders = [];

    /**
     * Create random float number
     * @param $min
     * @param $max
     * @return float|int
     */
    public static function randomFloat($min, $max)
    {
        $num = $min + lcg_value() * ($max - $min);
        $randomFloat = sprintf("%.1f", $num);

        return $randomFloat;
    }

    /**
     * Generate random string
     * @param int $length
     * @param string $type
     * @return string
     */
    public static function random($length = 10, $type = 'mixed')
    {
        if ($type == 'mixed') {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } elseif ($type == 'uper') {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } elseif ($type == 'lower') {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        } elseif ($type == 'numbers') {
            $characters = '0123456789';
        }
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Generate selector code for store record in database
     * @return bool|string
     */
    public static function selector()
    {
        $str = substr(md5(uniqid(mt_rand(), true)), 0, 8);
        $str = self::random($length = 2, 'lower') . $str;
        return $str;
    }

    /**
     * Return tracking code string
     * @param string $prefix
     * @return bool|string
     */
    public static function trackingCode($prefix = '')
    {
        $time = substr(time(), 0, 6);
        $hash = $prefix . '-' . $time . self::random(4, 'uper');
        return $hash;
    }

    /**
     * Generate unique string
     * @return string
     */
    public static function unique()
    {
        $time = (new Request())->getTimestamp();
        $random = self::random(10);
        return md5($time . $random);
    }

    /**
     * If array is multidimensional or not
     * @param array $array
     * @return bool
     */
    public static function multidimensional($array = [])
    {
        if (count($array) == count($array, COUNT_RECURSIVE)) {
            return false;
        }
        return true;
    }

    /**
     * Replace latina numbers with persian numbers
     */
    public static function pTpEnNumber($string)
    {
        $numbers = [
            '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'
        ];
        $pNumbers = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        return str_replace($numbers, $pNumbers, $string);
    }

    /**
     * Replace latina numbers with persian numbers
     */
    public static function pNumbers($string)
    {
        $numbers = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        $pNumbers = [
            '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'
        ];
        return str_replace($numbers, $pNumbers, $string);
    }

    /**
     * Select part of string from zero
     * @param $string
     * @param $length
     * @return string
     */
    public static function pString($string = '', $length = 255)
    {
        $string = strip_tags($string);
        return substr($string, 0, $length);
    }

    /**
     * Generate unique code
     * @param $string
     * @return string
     */
    public static function shortUnique($string = '')
    {
        $string = $string . self::random(7);
        return $string;
    }

    /**
     * Fetch list of direct directories from selected directory
     * @param $dir
     * @return mixed
     */
    public static function directories($dir)
    {
        $list = [];
        if (is_dir($dir)) {
            $ffs = scandir($dir);
            foreach ($ffs as $key => $value) {
                $directory = $dir . DS . $value;
                if ($value !== '.' and $value !== '..') {
                    if (is_dir($directory)) {
                        $list[] = $value;
                    }
                }
            }
        }
        return $list;
    }

    /**
     * Fetch list of directories and sub directories
     * @param $dir
     * @param string $type
     * @return array|boolean
     */
    public static function folderList($dir, $type = 'base')
    {
        if (is_dir($dir)) {
            $ffs = scandir($dir);

            unset($ffs[array_search('.', $ffs, true)]);
            unset($ffs[array_search('..', $ffs, true)]);

            // prevent empty ordered elements
            if (count($ffs) < 1)
                return false;
            if ($type == 'base') {
                foreach ($ffs as $ff) {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $ff)) {
                        self::$listFolders[] = $dir . DIRECTORY_SEPARATOR . $ff;
                        self::folderList($dir . DIRECTORY_SEPARATOR . $ff);
                    }
                }
            } else {
                $paths = [];
                foreach ($ffs as $ff) {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $ff)) {
                        $paths[] = $dir . DIRECTORY_SEPARATOR . $ff;
                        self::folderList($dir . DIRECTORY_SEPARATOR . $ff, 'self');
                    }
                }
                return $paths;
            }

        }
    }

    /**
     * Calculate and return hash string
     * @param $password
     * @param $salt
     * @return string
     */
    public static function encrypt($password, $salt)
    {
        $hash = crypt($password, '$6$rounds=5000$' . $salt . '$');
        return $hash;
    }

    /**
     * Check password to equal string
     * @param $password
     * @param $equal
     * @param $salt
     * @return bool
     */
    public static function checkPassword($password, $equal, $salt)
    {
        /** If selected password and original password is equal */
        if (hash_equals(self::encrypt($password, $salt), $equal)) {
            return true;
        } else {
            /** If selected password and original password is not equal */
            return false;
        }
    }

    /**
     * Copy entire contents of a directory
     * @param $src
     * @param $dst
     */
    public static function copyDirectory($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . DS . $file)) {
                    self::copyDirectory($src . DS . $file, $dst . DS . $file);
                } else {
                    @copy($src . DS . $file, $dst . DS . $file);
                }
            }
        }
        @closedir($dir);
    }

    /**
     * Delete whole of directory
     * @param $dir
     */
    public static function rmDir($dir)
    {
        if (is_dir($dir)) {
            $objects = @scandir($dir);
            if (is_array($objects)) {
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (@filetype($dir . "/" . $object) == "dir") self::rmDir($dir . "/" . $object); else @unlink($dir . "/" . $object);
                    }
                }
            }
            @reset($objects);
            if (file_exists($dir)) {
                @rmdir($dir);
            }
        }
    }

    /**
     * If string is json or not
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Calculate size of selected directory
     * @param $dir
     * @return int
     */
    public static function folderSize($dir)
    {
        $dir = rtrim(str_replace('\\', '/', $dir), '/');

        if (is_dir($dir) === true) {
            $totalSize = 0;
            $os = strtoupper(substr(PHP_OS, 0, 3));
            // If on a Unix Host (Linux, Mac OS)
            if ($os !== 'WIN') {
                $io = popen('/usr/bin/du -sb ' . $dir, 'r');
                if ($io !== false) {
                    $totalSize = intval(fgets($io, 80));
                    pclose($io);
                    return $totalSize;
                }
            }
            // If on a Windows Host (WIN32, WINNT, Windows)
            if ($os === 'WIN' && extension_loaded('com_dotnet')) {
                $obj = new \COM('scripting.filesystemobject');
                if (is_object($obj)) {
                    $ref = $obj->getfolder($dir);
                    $totalSize = $ref->size;
                    $obj = null;
                    return $totalSize;
                }
            }
            // If System calls did't work, use slower PHP 5
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($files as $file) {
                $totalSize += $file->getSize();
            }
            return $totalSize;
        } else if (is_file($dir) === true) {
            return filesize($dir);
        }
    }

    /**
     * Return count of file in directory
     * @param $path
     * @return int
     */
    public static function fileCount($path)
    {
        $fi = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);
        $fileCount = iterator_count($fi);
        return $fileCount;
    }

    /**
     * @param array $array
     * @param array|string $parents
     * @param string $glue
     * @return mixed
     */
    public static function array_get_value(array &$array, $parents, $glue = '.')
    {
        if (!is_array($parents)) {
            $parents = explode($glue, $parents);
        }

        $ref = &$array;

        foreach ((array)$parents as $parent) {
            if (is_array($ref) && array_key_exists($parent, $ref)) {
                $ref = &$ref[$parent];
            } else {
                return null;
            }
        }
        return $ref;
    }

    /**
     * @param array $array
     * @param array|string $parents
     * @param mixed $value
     * @param string $glue
     */
    public static function array_set_value(array &$array, $parents, $value, $glue = '.')
    {
        if (!is_array($parents)) {
            $parents = explode($glue, (string)$parents);
        }

        $ref = &$array;

        foreach ($parents as $parent) {
            if (isset($ref) && !is_array($ref)) {
                $ref = array();
            }

            $ref = &$ref[$parent];
        }

        $ref = $value;
    }

    /**
     * @param array $array
     * @param array|string $parents
     * @param string $glue
     */
    public static function array_unset_value(&$array, $parents, $glue = '.')
    {
        if (!is_array($parents)) {
            $parents = explode($glue, $parents);
        }

        $key = array_shift($parents);

        if (empty($parents)) {
            unset($array[$key]);
        } else {
            self::array_unset_value($array[$key], $parents);
        }
    }

    /**
     * Json output
     * @param $array
     * @param string $type
     * @return false|string
     */
    public static function jsonOut($array, $type = 'return')
    {
        if ($type === 'print') {
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * json string to array
     * @param $json
     * @param string $type
     * @return false|string
     */
    public static function jsonIn($json)
    {
        return json_decode($json, true);
    }

    /**
     * Print an array or string with '<pre>' tag to display beauty
     * @param array $value
     */
    public static function bePrint($value = [])
    {
        echo '<pre>';
        print_r($value);
        die;
    }

    /**
     * Display money format in persian method
     * @param $number
     * @return string
     */
    public static function persianMoney($number)
    {
        return self::pNumbers(number_format($number));
    }

    /**
     * Check if file exist in public directory on website
     * @param $file
     * @return bool
     */
    public static function fileExist($file)
    {
        $file = str_ireplace(['/', '%28', '%29'], [DS, '(', ')'], $file);
        $file = APP_PATH . 'public' . $file;
        if (file_exists($file) and is_file($file)) {
            return true;
        } else {
            return false;
        }
    }
}
