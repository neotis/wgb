<?php
/**
 * Created by PhpStorm.
 * Date: 2/13/2019
 * Time: 8:18 PM
 */

namespace Neotis\Core\Form\Options;

use Neotis\Cli\Neotis;

class Category extends Neotis
{
    /**
     * Define status of element required
     * @param bool $value
     * @return Category
     */
    public function required($value = false)
    {
        return $this;
    }

    /**
     * Minimum character of text area
     * @param int $value
     * @return Category
     */
    public function count($value = 1000)
    {
        return $this;
    }
}
