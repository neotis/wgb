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

class Password extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Password
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Define regex of value
     * @param string $value
     * @return Password
     */
    public function regex($value = '')
    {
        return $this;
    }
}
