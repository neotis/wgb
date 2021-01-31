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

class Multiple extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Multiple
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Minimum count of select item
     * @param $value
     * @return Multiple
     */
    public function min($value = 1)
    {
        return $this;
    }

    /**
     * Maximum count of select item
     * @param $value
     * @return Multiple
     */
    public function max($value = 10)
    {
        return $this;
    }
}
