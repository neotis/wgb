<?php

namespace Neotis\Core\Http;

use Neotis\Core\Neotis;
use Neotis\Interfaces\Core\Request\Adapter;

/**
 * Encapsulates request information for easy and secure access from application controllers.
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 */
class Request extends Neotis implements Adapter
{
    /**
     * Type of method
     * @var null
     */
    public static $method = null;

    /**
     * Set status of file watcher
     * If this variable has the "True" value, the file watcher will be activated.
     * @var bool
     */
    public static $watcher = false;

    /**
     * Set type of requested method
     * @param string $value
     */
    public static function setMethod($value = 'GET')
    {
        self::$method = $value;
    }

    /**
     * Set request method as static property
     */
    public static function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            self::$method = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        return self::$method;
    }

    /**
     * Return query from url address
     * @param string $type
     * @return string
     */
    public static function getUri($type = 'query')
    {
        if ($type == 'query') {
            return $_SERVER['REQUEST_URI'];
        } else {
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            return $actual_link;
        }
    }

    /**
     * Encode url
     * @param $string
     * @return string|string[]
     */
    public static function flashEncode($string)
    {
        $search = [
            '/', '\\', ' ', '.', '*', '%', '(', ')'
        ];
        $replace = ['', '', '-', '-', '-', '_', '_', '_'];
        $output = str_ireplace($search, $replace, $string);
        $output = urlencode($output);
        return $output;
    }

    /**
     * Encode url
     * @param $string
     * @return string|string[]
     */
    public static function flashEncodeReverse($string)
    {
        $search = [
            '/', '\\', ' ', '.', '*', '%', '(', ')'
        ];
        $replace = ['', '', '-', '-', '-', '_', '_', '_'];
        $output = str_ireplace($replace, $search, $string);
        $output = urlencode($output);
        return $output;
    }

    /**
     * Return ip address of user
     * @return string
     */
    public static function getUserIp()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        if(isset($_SERVER['REMOTE_ADDR'])){
            $remote = $_SERVER['REMOTE_ADDR'];
        }else{
            $remote = 127;
        }


        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Reurn current account
     * @return mixed
     */
    public static function account()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Return time stamp of current time
     * @return int
     */
    public static function getTimestamp()
    {
        return time();
    }

    /**
     * Get user agent information of user and browser
     * @return mixed
     */
    public static function getUseragent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        } else {
            return 'CLI';
        }
    }

    /**
     * Returns value from $_REQUEST without sanitizing
     * @param string $name
     * @return bool
     */
    public static function get($name = '')
    {
        //if defined $name to return value
        if (isset($name) and !empty($name)) {
            if (!isset($_REQUEST[$name])) {
                return false;
            }
            return $_REQUEST[$name];
        } else {//if not defined $name to return value return all data of $_GET
            return $_REQUEST;
        }
    }

    /**
     * Returns value from $_POST without sanitizing
     * @param string $name
     * @param string $type
     * @return mixed
     */
    public static function getPost($name = '', $type = 'normal')
    {
        if (Header::$json or empty($_POST)) {
            $obj = json_decode(key($_POST), true);
            $request_body = file_get_contents('php://input');
            if (!empty($obj)) {
                $obj = $_POST;
                //if defined $name to return value
                if (isset($name) and !empty($name)) {
                    if (!isset($obj[$name])) {
                        return false;
                    }
                    return $obj[$name];
                } else {//if not defined $name to return value return all data of $_GET
                    return $obj;
                }
            } else {
                parse_str($request_body, $obj);
                //if defined $name to return value
                if (isset($name) and !empty($name)) {
                    if (!isset($obj[$name])) {
                        return false;
                    }
                    return $obj[$name];
                } elseif (is_array($obj) and !empty($obj)) {//if not defined $name to return value return all data of $_GET
                    return $obj;
                } else {
                    //if defined $name to return value
                    if (isset($name) and !empty($name)) {
                        if (!isset($_POST[$name])) {
                            return false;
                        }
                        return $_POST[$name];
                    } else {//if not defined $name to return value return all data of $_GET
                        return $_POST;
                    }
                }
            }
        } else {
            //if defined $name to return value
            if (isset($name) and !empty($name)) {
                if (!isset($_POST[$name])) {
                    return false;
                }
                return $_POST[$name];
            } else {//if not defined $name to return value return all data of $_GET
                return $_POST;
            }
        }
    }

    /**
     * Returns value from $_FILES without sanitizing
     * @param string $name
     * @return bool | array
     */
    public static function getFiles($name = '')
    {
        //if defined $name to return value
        if (isset($name) and !empty($name)) {
            if (!isset($_FILES[$name])) {
                return false;
            }
            return $_FILES[$name];
        } else {//if not defined $name to return value return all data of $_GET
            return $_FILES;
        }
    }


    /**
     * Returns value from $_PUT without sanitizing
     * @param string $name
     * @return mixed
     */
    public static function getPut($name = '')
    {
        // Fetch content and determine boundary
        $raw_data = file_get_contents('php://input');
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));
        // Fetch each part
        if (!empty($boundary)) {
            $parts = array_slice(explode($boundary, $raw_data), 1);
        } else {
            $parts = [];
        }
        $data = [];

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break;

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' ');
            }

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                    $headers['content-disposition'],
                    $matches
                );
                list(, $type, $name) = $matches;
                isset($matches[4]) and $filename = $matches[4];

                // handle your fields here
                switch ($name) {
                    // this is a file upload
                    case 'userfile':
                        file_put_contents($filename, $body);
                        break;

                    // default for all other files is to populate $data
                    default:
                        $data[$name] = substr($body, 0, strlen($body) - 2);
                        break;
                }
            }

        }
        if (is_array($data) and !empty($data)) {
            if (isset($name) and !empty($name)) {
                if (!isset($data[$name])) {
                    return false;
                }
                return $data[$name];
            }
        } else {
            parse_str($raw_data, $post_vars);
            //if defined $name to return value
            if (isset($name) and !empty($name)) {
                if (!isset($post_vars[$name])) {
                    return false;
                } else {
                    return $post_vars[$name];
                }
            } else {
                return $post_vars;
            }
        }

    }

    /**
     * Returns value from $_GET without sanitizing
     * @param $name
     * @return string
     */
    public static function getQuery($name = '', $type = 'parse')
    {
        //if defined $name to return value
        if (isset($name) and !empty($name)) {
            if (!isset($_GET[$name])) {
                return false;
            }
            if ($type === 'parse') {
                return self::getDecode($_GET[$name]);
            } else {
                return $_GET[$name];
            }

        } else {//if not defined $name to return value return all data of $_GET
            if ($type === 'parse') {
                $array = [];
                foreach ($_GET as $key => $value) {
                    $array[$key] = self::getDecode($value);
                }
                return $array;
            } else {
                return $_GET;
            }
        }
    }

    /**
     * parse all dangeorus tag
     * @param $string
     * @return string
     */
    protected static function getDecode($string)
    {
        $search = [
            '$', '&', '+', ':', ';',
            '=', '?', '@', '<', '>', '#', '%',
            '|', '\\', '^', '~', '[',
            ']', '`'
        ];
        $replace = '';
        return str_ireplace($search, $replace, $string);
    }

    /**
     * Returns value from $_SERVER without sanitizing
     * @param string $name
     * @return bool
     */
    public static function getServer($name = '')
    {
        //if defined $name to return value
        if (isset($name) and !empty($name)) {
            if (!isset($_SERVER[$name])) {
                return false;
            }
            return $_SERVER[$name];
        } else {//if not defined $name to return value return all data of $_GET
            return $_SERVER;
        }
    }
}
