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

class TinyMce extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return TinyMce
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param bool $value
     * @return TinyMce
     */
    public function min($value = false)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param bool $value
     * @return TinyMce
     */
    public function max($value = false)
    {
        return $this;
    }
}
