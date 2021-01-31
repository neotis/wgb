<?php
/**
 * Title manager
 * Created by PhpStorm.

 * Date: 11/22/2018
 * Time: 11:56 AM
 * Neotis framework
 */

namespace Neotis\Core\Template\Result;


trait Title
{
    /**
     * Store base title
     * @var string
     */
    private static $baseTitle = '';

    /**
     * Store title of page
     * @var string
     */
    private static $title = '';

    /**
     * Define base title of template view
     * @param string $name
     */
    public static function setBaseTitle($name = '')
    {
        self::$baseTitle = $name;
    }

    /**
     * Define title of current page
     * @param string $name
     */
    public static function setTitle($name = '')
    {
        self::$title = $name;
    }

    /**
     * Get title of template and website view
     */
    public static function getTitle()
    {
        if (empty(self::$title)) {
            return '<title>' . self::$baseTitle . '</title>';
        } else {
            return '<title>' . self::$title . ' - ' . self::$baseTitle . '</title>';
        }
    }

    /**
     * Get title of template and website view
     */
    public static function getPureTitle()
    {
        if (empty(self::$title)) {
            return self::$baseTitle;
        } else {
            return  self::$title . ' - ' . self::$baseTitle;
        }
    }
}
