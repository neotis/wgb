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

class CkEditor extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return CkEditor
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param bool $value
     * @return CkEditor
     */
    public function min($value = false)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param bool $value
     * @return CkEditor
     */
    public function max($value = false)
    {
        return $this;
    }
}
