<?php
/**
 * Neotis base class
 * Created by PhpStorm.

 * Date: 9/29/2017
 * Time: 12:17 AM
 */

namespace Neotis\Tests;

use PHPUnit\Framework\TestCase;

class Neotis extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
