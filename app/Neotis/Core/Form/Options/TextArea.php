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

class TextArea extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return TextArea
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param int $value
     * @return TextArea
     */
    public function min($value = 5)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param int $value
     * @return TextArea
     */
    public function max($value = 200)
    {
        return $this;
    }
}
