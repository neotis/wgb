<?php
/**
 * Create text element form
 * Created by PhpStorm.

 * Date: 2/1/2019
 * Time: 6:44 PM
 * Neotis framework
 */

namespace Neotis\Core\Form\Options;

use Neotis\Cli\Neotis;

class File extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return File
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Define url for upload image
     * @param string $url
     * @return File
     */
    public function uploadPath($url = '')
    {
        return $this;
    }

    /**
     * Define url for save uploaded image
     * @param string $url
     * @return File
     */
    public function savePath($url = '')
    {
        return $this;
    }

    /**
     * Define name of uploaded image
     * @param string $name
     * @return File
     */
    public function saveName($name = '')
    {
        return $this;
    }

    /**
     * Define maximum size of uploaded image as (kb)
     * Define 0 value for infinite
     * @param int $value
     * @return File
     */
    public function size($value = 0)
    {
        return $this;
    }

    /**
     * Define extension of uploaded image
     * Separate with comma
     * @param string $value
     * @return File
     */
    public function extensions($value = 'png,gif,jpg,jpeg')
    {
        return $this;
    }
}
