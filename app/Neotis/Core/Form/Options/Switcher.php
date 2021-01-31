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

class Switcher extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Switcher
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Define array of switcher value
     * @param array $array
     * @return Switcher
     */
    public function data($array = [])
    {
        return $this;
    }

    /**
     * Define array of switcher value
     * @param $value
     * @return Switcher
     */
    public function defaultValue($value = '')
    {
        return $this;
    }
}
