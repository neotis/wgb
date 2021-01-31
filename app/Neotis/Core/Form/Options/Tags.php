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

class Tags extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Tags
     */
    public function required($value = false)
    {
        return $this;
    }


    /**
     * Select table for fetch information array of data from database
     * @param string $name
     * @return Tags
     */
    public function dataTable($name = "")
    {
        return $this;
    }

    /**
     * Define column name for fill data array of element
     * @param array $column
     * @return Tags
     */
    public function tableColumn($column = [])
    {
        return $this;
    }

    /**
     * Define array as element information
     * @param array $data
     * @return Tags
     */
    public function dataArray($data = [])
    {
        return $this;
    }

    /**
     * Define array of switcher value
     * @param $value
     * @return Tags
     */
    public function defaultValue($value = '')
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param $value
     * @return Tags
     */
    public function min($value = '')
    {
        return $this;
    }

    /**
     * Maximum character of text area
     * @param $value
     * @return Tags
     */
    public function max($value = '')
    {
        return $this;
    }
}
