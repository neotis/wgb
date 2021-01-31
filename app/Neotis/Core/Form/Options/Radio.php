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

class Radio extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Radio
     */
    public function required($value = false)
    {
        return $this;
    }


    /**
     * Select table for fetch information array of data from database
     * @param string $name
     * @return Radio
     */
    public function dataTable($name = "")
    {
        return $this;
    }

    /**
     * Define column name for fill data array of element
     * @param array $column
     * @return Radio
     */
    public function tableColumn($column = [])
    {
        return $this;
    }

    /**
     * Define array as element information
     * @param array $data
     * @return Radio
     */
    public function dataArray($data = [])
    {
        return $this;
    }

    /**
     * Define array of switcher value
     * @param $value
     * @return Radio
     */
    public function defaultValue($value = '')
    {
        return $this;
    }
}
