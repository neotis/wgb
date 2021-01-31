<?php
namespace Neotis\Interfaces\Core\Header;

/**
 * Header adapter interface
 * Created by PhpStorm.
 * Date: 10/5/2018
 * Time: 3:55 PM
 */

interface Adapter
{
    /**
     * Return Api Key code
     * @return string
     */
    public static function getApiKey();

    /**
     * Return authorization code
     * @return bool
     */
    public static function getAuthorization();

    /**
     * Return json status
     * @return bool
     */
    public static function getJson();

    /**
     * Return requests array
     * @return array
     */
    public static function getRequests();

    /**
     * Store header information in $requests
     */
    public static function setRequests();

    /**
     * Define status of json request and response then store in $json static
     */
    public static function json();

    /**
     * Define basic header to response
     */
    public static function setHeaders();

    /**
     * Define and find authorization code then store in $authorization static
     */
    public static function authorization();

    /**
     * Add header to response
     * @param $header
     * @param bool $replace
     * @param int $http_response_code
     */
    public static function add($header, $replace, $http_response_code);
}
