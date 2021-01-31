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

class AutoComplete extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return AutoComplete
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Define regex of value
     * @param string $value
     * @return AutoComplete
     */
    public function regex($value = '')
    {
        return $this;
    }

    /**
     * Select table for fetch information array of data from database
     * @param string $name
     * @return $this
     */
    public function dataTable($name = "")
    {
        return $this;
    }

    /**
     * Define column name for fill data array of element
     * @param array $column
     * @return $this
     */
    public function tableColumn($column = [])
    {
        return $this;
    }

    /**
     * Define array as element information
     * @param array $data
     * @return AutoComplete
     */
    public function dataArray($data = [])
    {
        return $this;
    }
}
