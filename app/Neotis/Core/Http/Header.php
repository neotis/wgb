<?php
/**
 * Header class
 * Created by PhpStorm.
 * User: Shnahin ataei
 * Date: 1/24/2018
 * Time: 5:13 PM
 * Neotis framework
 */

namespace Neotis\Core\Http;

use Neotis\Core\Neotis;
use Neotis\Interfaces\Core\Header\Adapter;

class Header extends Neotis implements Adapter
{
    /**
     * Store all header request
     * @var array
     */
    public static $requests = [];

    /**
     * If request header is json and want response as json
     * @var bool
     */
    public static $json = false;

    /**
     * Store authorization key
     * @var bool
     */
    public static $authorization = '';

    /**
     * Store api key
     * @var string
     */
    public static $apiKey = '';

    /**
     * Store http status for decide display page
     * @var int
     */
    public static $httpCode = 200;

    /**
     * Status of partial request for display partial of template factory
     * @var bool
     */
    public static $partial = false;

    /**
     * Status of pure html request
     * @var bool
     */
    public static $pure = false;

    /**
     * Status of partial request for display partial of template factory
     * @var bool
     */
    public static $component = false;

    /**
     * Return Api Key code
     * @return string
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Return authorization code
     * @return bool
     */
    public static function getAuthorization()
    {
        return self::$authorization;
    }

    /**
     * Return json status
     * @return bool
     */
    public static function getJson()
    {
        return self::$json;
    }

    /**
     * Return requests array
     * @return array
     */
    public static function getRequests()
    {
        return self::$requests;
    }

    /**
     * Put header information to $requests
     */
    public static function setRequests()
    {
        self::$requests = getallheaders();
    }

    /**
     * Define status of json request and response then store in $json static
     */
    public static function json()
    {
        if ((isset(self::$requests['Content-Type']) and (self::$requests['Content-Type'] == 'application/json') or (isset(self::$requests['Content-Type']) and self::$requests['Content-Type'] == 'application/x-www-form-urlencoded,application/json'))) {
            self::add("Content-Type: application/json");
            self::$json = true;
        } elseif (isset(self::$requests['Accept']) and (self::$requests['Accept'] == 'application/json' or self::$requests['Accept'] == 'application/x-www-form-urlencoded,application/json')) {
            self::add("Content-Type: application/json");
            self::$json = true;
        } elseif (isset(self::$requests['Response-Type']) and (self::$requests['Response-Type'] == 'application/json' or self::$requests['Response-Type'] == 'application/x-www-form-urlencoded,application/json')) {
            self::add("Content-Type: application/json");
            self::$json = true;
        }
    }

    /**
     * Define basic header to response
     */
    public static function setHeaders()
    {
        self::add("Access-Control-Allow-Origin: *");
        self::add("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        self::add("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, X-Auth-Token, X-SomeHeader, X-Session-Id");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            self::add("HTTP/1.1 200 OK");
            exit;
        }
    }

    /**
     * Define and find authorization code then store in $authorization static
     */
    public static function authorization()
    {
        if (isset(self::$requests['Authorization']) and !empty(self::$requests['Authorization'])) {
            $code = explode(' ', self::$requests['Authorization']);

            if (strtolower($code[0]) === 'basic') {
                self::$authorization = $code[1];
            }
            if ($code[0] === 'APIKEY') {
                self::$apiKey = $code[1];
            }
        }
    }

    /**
     * Define status of partial request from request header
     */
    public static function partial()
    {
        $value = '';
        if (isset(self::$requests['Request-Type']) and !empty(self::$requests['Request-Type'])) {
            $value = self::$requests['Request-Type'];
        } elseif (isset(self::$requests['request-type']) and !empty(self::$requests['request-type'])) {
            $value = self::$requests['request-type'];
        }

        if ($value === 'partial') {
            self::$partial = true;
        }
        if ($value === 'component') {
            self::$component = true;
        }
    }

    /**
     * Define status of partial request from request header
     */
    public static function pureHtml()
    {
        $value = '';
        if (isset(self::$requests['Html-Request']) and !empty(self::$requests['Html-Request'])) {
            $value = self::$requests['Html-Request'];
        } elseif (isset(self::$requests['html-request']) and !empty(self::$requests['html-request'])) {
            $value = self::$requests['html-request'];
        }

        if ($value === 'pure') {
            self::$pure = true;
        }
    }

    /**
     * Add header to response
     * @param $header
     * @param bool $replace
     * @param int $http_response_code
     */
    public static function add($header, $replace = true, $http_response_code = 200)
    {
        self::$httpCode = $http_response_code;
        @header($header, $replace, $http_response_code);
    }

    /**
     * Add header to response with HTTP code
     * @param $httpCode
     */
    public static function codesHeader($httpCode)
    {
        $codes[100] = "100 Continue";
        $codes[101] = "101 Switching Protocols";
        $codes[102] = "102 Processing (WebDAV; RFC 2518)";
        $codes[103] = "103 Early Hints (RFC 8297)";
        $codes[200] = "200 OK";
        $codes[201] = "201 Created";
        $codes[202] = "202 Accepted";
        $codes[203] = "203 Non-Authoritative Information (since HTTP/1.1)";
        $codes[204] = "204 No Content";
        $codes[205] = "205 Reset Content";
        $codes[206] = "206 Partial Content (RFC 7233)";
        $codes[207] = "207 Multi-Status (WebDAV; RFC 4918)";
        $codes[208] = "208 Already Reported (WebDAV; RFC 5842)";
        $codes[226] = "226 IM Used (RFC 3229)";
        $codes[300] = "300 Multiple Choices";
        $codes[301] = "301 Moved Permanently";
        $codes[302] = "302 Found (Previously 'Moved temporarily')";
        $codes[303] = "303 See Other (since HTTP/1.1)";
        $codes[304] = "304 Not Modified (RFC 7232)";
        $codes[305] = "305 Use Proxy (since HTTP/1.1)";
        $codes[306] = "306 Switch Proxy";
        $codes[307] = "307 Temporary Redirect (since HTTP/1.1)";
        $codes[308] = "308 Permanent Redirect (RFC 7538)";
        $codes[400] = "400 Bad Request";
        $codes[401] = "401 Unauthorized (RFC 7235)";
        $codes[402] = "402 Payment Required";
        $codes[403] = "403 Forbidden";
        $codes[404] = "404 Not Found";
        $codes[405] = "405 Method Not Allowed";
        $codes[406] = "406 Not Acceptable";
        $codes[407] = "407 Proxy Authentication Required (RFC 7235)";
        $codes[408] = "408 Request Timeout";
        $codes[409] = "409 Conflict";
        $codes[410] = "410 Gone";
        $codes[411] = "411 Length Required";
        $codes[412] = "412 Precondition Failed (RFC 7232)";
        $codes[413] = "413 Payload Too Large (RFC 7231)";
        $codes[414] = "414 URI Too Long (RFC 7231)";
        $codes[415] = "415 Unsupported Media Type";
        $codes[416] = "416 Range Not Satisfiable (RFC 7233)";
        $codes[417] = "417 Expectation Failed";
        $codes[418] = "418 I'm a teapot (RFC 2324, RFC 7168)";
        $codes[421] = "421 Misdirected Request (RFC 7540)";
        $codes[422] = "422 Unprocessable Entity (WebDAV; RFC 4918)";
        $codes[423] = "423 Locked (WebDAV; RFC 4918)";
        $codes[424] = "424 Failed Dependency (WebDAV; RFC 4918)";
        $codes[426] = "426 Upgrade Required";
        $codes[428] = "428 Precondition Required (RFC 6585)";
        $codes[429] = "429 Too Many Requests (RFC 6585)";
        $codes[431] = "431 Request Header Fields Too Large (RFC 6585)";
        $codes[451] = "451 Unavailable For Legal Reasons (RFC 7725)";
        $codes[500] = "500 Internal Server Error";
        $codes[501] = "501 Not Implemented";
        $codes[502] = "502 Bad Gateway";
        $codes[503] = "503 Service Unavailable";
        $codes[504] = "504 Gateway Timeout";
        $codes[505] = "505 HTTP Version Not Supported";
        $codes[506] = "506 Variant Also Negotiates (RFC 2295)";
        $codes[507] = "507 Insufficient Storage (WebDAV; RFC 4918)";
        $codes[508] = "508 Loop Detected (WebDAV; RFC 5842)";
        $codes[510] = "510 Not Extended (RFC 2774)";
        $codes[103] = "103 Checkpoint";
        $codes[218] = "218 This is fine (Apache Web Server)";
        $codes[419] = "419 Page Expired (Laravel Framework)";
        $codes[420] = "420 Method Failure (Spring Framework)+";
        $codes[450] = "450 Blocked by Windows Parental Controls (Microsoft)";
        $codes[498] = "498 Invalid Token (Esri)";
        $codes[499] = "499 Token Required (Esri)";
        $codes[509] = "509 Bandwidth Limit Exceeded (Apache Web Server/cPanel)";
        $codes[526] = "526 Invalid SSL Certificate";
        $codes[530] = "530 Site is frozen";
        $codes[598] = "598 (Informal convention) Network read timeout error";
        $codes[440] = "440 Login Time-out";
        $codes[449] = "449 Retry With";
        $codes[451] = "451 Redirect";
        $codes[444] = "444 No Response";
        $codes[494] = "494 Request header too large";
        $codes[495] = "495 SSL Certificate Error";
        $codes[496] = "496 SSL Certificate Required";
        $codes[497] = "497 HTTP Request Sent to HTTPS Port";
        $codes[499] = "499 Client Closed Request";
        $codes[520] = "520 Unknown Error";
        $codes[521] = "521 Web Server Is Down";
        $codes[522] = "522 Connection Timed Out";
        $codes[523] = "523 Origin Is Unreachable";
        $codes[524] = "524 A Timeout Occurred";
        $codes[525] = "525 SSL Handshake Failed";
        $codes[526] = "526 Invalid SSL Certificate";
        $codes[527] = "527 Railgun Error";
        $codes[530] = "530 Origin DNS Error";
        self::$httpCode = $httpCode;
        header('HTTP/1.0 ' . $codes[$httpCode], true, $httpCode);
    }

    /**
     * Redirect page to another path
     * @param string $url
     */
    public static function redirect($url = '')
    {
        self::add('Location: ' . $url, true, 308);
        exit;
    }
}
