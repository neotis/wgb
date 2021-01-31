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

class Image extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Image
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Define url for upload image
     * @param string $url
     * @return Image
     */
    public function uploadPath($url = '')
    {
        return $this;
    }

    /**
     * Define url for save uploaded image
     * @param string $url
     * @return Image
     */
    public function savePath($url = '')
    {
        return $this;
    }

    /**
     * Define name of uploaded image
     * @param string $name
     * @return $this
     */
    public function saveName($name = '')
    {
        return $this;
    }

    /**
     * Define maximum size of uploaded image as (kb)
     * Define 0 value for infinite
     * @param int $value
     * @return Image
     */
    public function size($value = 0)
    {
        return $this;
    }

    /**
     * Define maximum width of uploaded image as (pixel)
     * Define 0 value for infinite
     * @param int $value
     * @return Image
     */
    public function width($value = 100)
    {
        return $this;
    }

    /**
     * Define maximum height of uploaded image as (pixel)
     * Define 0 value for infinite
     * @param int $value
     * @return Image
     */
    public function height($value = 100)
    {
        return $this;
    }

    /**
     * Define aspect ration for uploaded image
     * Define 0 value for escape
     * @param int $value
     * @return Image
     */
    public function aspect($value = 0)
    {
        return $this;
    }

    /**
     * Define extension of uploaded image
     * Separate with comma
     * @param string $value
     * @return Image
     */
    public function extensions($value = 'png,gif,jpg,jpeg')
    {
        return $this;
    }
}
