<?php
/**
 * Created by PhpStorm.
 * Date: 2/13/2019
 * Time: 8:18 PM
 */

namespace Neotis\Core\Form\Options;

use Neotis\Cli\Neotis;

class Map extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Map
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param int $value
     * @return Map
     */
    public function lng($value = 1000)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param int $value
     * @return Map
     */
    public function lat($value = 1000)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param int $value
     * @return Map
     */
    public function zoom($value = 1000)
    {
        return $this;
    }
}
