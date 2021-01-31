<?php
/**
 * Adapter form for user
 * Created by PhpStorm.

 * Date: 2/1/2019
 * Time: 12:46 AM
 * Neotis framework
 */

namespace Neotis\Core\Form;

use Neotis\Cli\Neotis;

class Adapter extends Neotis
{
    /**
     * Store final array of created form
     * @var array
     */
    public static $form = [];

    /**
     * Store name of element
     * @var string
     */
    public static $name = '';

    /**
     * Define name of form element
     * @param string $name
     * @return Type
     */
    public function create($name = '')
    {
        self::$name = $name;
        return (new Type());
    }
}
