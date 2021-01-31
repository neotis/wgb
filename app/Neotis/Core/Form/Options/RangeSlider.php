<?php
/**
 * Created by PhpStorm.
 * Date: 2/13/2019
 * Time: 8:18 PM
 */

namespace Neotis\Core\Form\Options;

use Neotis\Cli\Neotis;

class RangeSlider extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return RangeSlider
     */
    public function required($value = false)
    {
        return $this;
    }


    /**
     * Minimum character of text area
     * @param int $value
     * @return RangeSlider
     */
    public function min($value = 1000)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param int $value
     * @return RangeSlider
     */
    public function max($value = 100000)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param int $value
     * @return RangeSlider
     */
    public function from($value = 1500)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param int $value
     * @return RangeSlider
     */
    public function to($value = 50000)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param int $value
     * @return RangeSlider
     */
    public function step($value = 100)
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param string $value
     * @return RangeSlider
     */
    public function kins($value = 'two')
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param string $value
     * @return RangeSlider
     */
    public function prefix($value = '$')
    {
        return $this;
    }
}
