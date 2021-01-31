<?php
/**
 * Manipulate where clause
 * Created by PhpStorm.

 * Date: 10/11/2018
 * Time: 2:00 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;

use Neotis\Core\Mvc\Model;

trait Join
{
    /**
     * Store where condition string
     * @var string
     */
    protected $onJoin = [];

    /**
     * Generate where condition
     * @param $table
     * @param $parameters
     * @return string
     */
    public function joinClause($table, $parameters)
    {
        $pCounter = $this->paramCount;
        $pCounter++;
        $where = $prefix = '';
        foreach($parameters as $key => $value){
            if(isset($value[3]) and $value[3] === 'column'){
                $where .= $prefix . "$value[0] $value[1] $value[2]";
            }else{
                $where .= $prefix . "$value[0] $value[1] $value[2]";
                $this->params[":$value[0]$pCounter"] = $value[2];
                $this->whereParams[":$value[0]$pCounter"] = $value[2];
            }
            $prefix = ' AND ';
        }
        $this->onJoin[] = " INNER JOIN $table ON $where";
    }
}
