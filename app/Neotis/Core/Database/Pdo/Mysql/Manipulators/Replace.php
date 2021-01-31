<?php
/**
 * Created by PhpStorm.

 * Date: 10/30/2018
 * Time: 2:09 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;


use Neotis\Core\Mvc\Model;

trait Replace
{
    /**
     * Replace information of record from selected table
     */
    protected function replacer()
    {
        $table = $this->tableName;
        $where = "WHERE `$table`.deleted = 0";

        if (!empty($this->where)) {//If where condition is exist and not empty
            $where = ' WHERE ' . $this->where;
        }

        $innerJoin = "";
        foreach($this->onJoin as $key => $value){
            $innerJoin .= $value;
        }

        $leftJoin = "";
        foreach($this->onLeftJoin as $key => $value){
            $leftJoin .= $value;
        }

        $rightJoin = "";
        foreach($this->onRightJoin as $key => $value){
            $rightJoin .= $value;
        }

        $fullJoin = "";
        foreach($this->onFullJoin as $key => $value){
            $fullJoin .= $value;
        }

        $search = [
            '{columns}',
            '{table}',
            '{where}',
            '{values}',
            '{group}',
            '{order}',
            '{limit}',
            '{offset}',
            '{tableSql}',
            '{columnSql}',
            '{innerJoin}',
            '{leftJoin}',
            '{rightJoin}',
            '{fullJoin}'
        ];
        $replace = [
            $this->columns,
            $table,
            $where,
            $this->values,
            $this->group,
            $this->order,
            $this->limitString,
            $this->offsetString,
            $this->tableSql,
            $this->columnSql,
            $innerJoin,
            $leftJoin,
            $rightJoin,
            $fullJoin
        ];

        $this->final = str_ireplace($search, $replace, $this->baseString);
        $this->finalWhere = str_ireplace($search, $replace, $this->finalWhere);


        $this->columns = '';
        $this->values = '';
        $this->group = '';
        $this->order = '';
        $this->limitString = '';
        $this->offsetString = '';
        $this->where = '';
        $this->prefix = '';
        $this->replaceTableName();
    }

    /**
     * Search array maker
     */
    private function searchMaker()
    {
        $search = [];
        foreach (Model::$tables as $key => $value) {
            $search[] = ' ' .$key. ' ';
            $search[] = '`' .$key . '`';
            $search[] = $key . '.';
        }
        return $search;
    }

    /**
     * Search array maker
     */
    private function replaceMaker()
    {
        $replace = [];
        foreach (Model::$tables as $key => $value) {
            $replace[] = ' ' .$value. ' ';
            $replace[] = '`' .$value . '`';
            $replace[] = ' ' .$value . '.';
        }
        return $replace;
    }

    /**
     * Replace name of model with real table name
     */
    private function replaceTableName()
    {
        $search = $this->searchMaker();
        $replace = $this->replaceMaker();

        $this->final = str_replace($search, $replace, $this->final);
    }
}
