<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 11/24/2018
 * Time: 12:27 PM
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;


trait Query
{
    /**
     * Bind value and parameter to sql query
     * @param array $array
     * @internal param $index
     * @internal param $value
     */
    public function bind($array = [])
    {
        foreach($array as $key => $value){
            $this->params[$key] = $value;
            $this->whereParams[$key] = $value;
        }
    }
}
