<?php
/**
 * Table column manager
 * Created by PhpStorm.

 * Date: 6/30/2018
 * Time: 12:26 AM
 * Neotis framework
 */

namespace Neotis\Core\Database;

use Neotis\Core\Mvc\Model;
use Neotis\Core\Neotis;

class Column extends Neotis
{
    /**
     * Create column for table
     * @param $table
     * @param $column
     * @param $type
     */
    public function create($table, $column, $type)
    {
        if ($type == 'text' or $type == 'autoComplete' or $type == 'multiple'
            or $type == 'tags' or $type == 'date' or $type == 'rangeSlider'
            or $type == 'proImage' or $type == 'category' or $type == 'hidden'
            or $type == 'image' or $type == 'file' or $type == 'selectBox'
            or $type == 'map' or $type == 'hidden'
        ) {
            $string = "ALTER TABLE `^$table` ADD `$column` VARCHAR(255) NULL AFTER `text`";
        } elseif($type == 'password') {
            $string = "ALTER TABLE `^$table` ADD `$column` VARCHAR(255) NULL AFTER `text`";
        } elseif($type == 'switcher' or $type == 'radio'
            or $type == 'checkBox') {
            if($type == 'switcher' or $type == 'checkBox') {
                $string = "ALTER TABLE `^$table` ADD `$column` tinyint(1) NULL DEFAULT 0 AFTER `text`";
            } elseif($type == 'radio') {
                $string = "ALTER TABLE `^$table` ADD `$column` tinyint(3) NULL DEFAULT 0 AFTER `text`";
            } else {
                $string = "ALTER TABLE `^$table` ADD `$column` int(11) NULL DEFAULT 0 AFTER `text`";
            }
        } elseif($type == 'textarea') {
            $string = "ALTER TABLE `^$table` ADD `$column` text NULL DEFAULT 0 AFTER `text`";
        } elseif($type == 'textEditor'){
            $string = "ALTER TABLE `^$table` ADD `$column` longtext NULL DEFAULT 0 AFTER `text`";
        }
        $sql['query'] = $string;
        (new Model())->query($sql);
    }

    /**
     * Check if column exist on selected table
     * @param $table
     * @param $column
     * @return mixed
     */
    public function exist($table, $column)
    {
        $tables = Model::$tables;
        $real = $tables[$table];
        $sql = "SHOW COLUMNS FROM $real";
        $insert = Model::$db->prepare($sql);
        $insert->execute();
        $result = $insert->fetchAll(\PDO::FETCH_ASSOC);
        $status = false;
        foreach($result as $key => $value){
            if($value['Field'] == $column){
                $status = true;
            }
        }
        return $status;
    }
}
