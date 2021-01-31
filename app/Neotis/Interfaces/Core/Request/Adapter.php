<?php
namespace Neotis\Interfaces\Core\Request;

/**
 * Request class interface
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 9:15 PM
 * Neotis framework
 */

interface Adapter
{
    /**
     * Return query from url address
     * @param string $type
     * @return string
     */
    public static function getUri($type = 'query');

    /**
     * Return ip address of user
     * @return string
     */
    public static function getUserIp();

    /**
     * Return time stamp of current time
     * @return int
     */
    public static function getTimestamp();

    /**
     * Get user agent information of user and browser
     * @return mixed
     */
    public static function getUseragent();

    /**
     * Returns value from $_REQUEST without sanitizing
     * @param string $name
     * @return bool
     */
    public static function get($name);

    /**
     * Returns value from $_POST without sanitizing
     * @param string $name
     * @param string $type
     * @return mixed
     */
    public static function getPost($name, $type);

    /**
     * Returns value from $_FILES without sanitizing
     * @param string $name
     * @return bool | array
     */
    public static function getFiles($name);


    /**
     * Returns value from $_PUT without sanitizing
     * @param string $name
     * @return mixed
     */
    public static function getPut($name);

    /**
     * Returns value from $_GET without sanitizing
     * @param string $name
     * @param string $type
     * @return mixed
     */
    public static function getQuery($name, $type);


    /**
     * Returns value from $_SERVER without sanitizing
     * @param string $name
     * @return bool
     */
    public static function getServer($name);

    /**
     * Get type of method request
     * @return mixed
     */
    public static function getMethod();
}
