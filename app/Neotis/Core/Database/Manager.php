<?php
/**
 * Database event manager
 * Created by PhpStorm.
 * User: developer
 * Date: 10/9/2018
 * Time: 3:35 PM
 */

namespace Neotis\Core\Database;

use Neotis\Core\Database\Pdo\Mysql\Eloquent;
use Neotis\Core\Mvc\Model;
use Neotis\Core\Neotis;

class Manager extends Neotis
{
    /**
     * Define eloquent to event manager
     */
    public function setEloquent()
    {
        //Model::$mysqlPdoEloquent = new Eloquent();
    }
}
