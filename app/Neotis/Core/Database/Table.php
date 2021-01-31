<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 11/15/2017
 * Time: 2:15 PM
 */

namespace Neotis\Core\Database;

use Neotis\Core\Adapter\Ini;
use Neotis\Core\Neotis;
use Neotis\Core\Router\Url;

class Table extends Neotis
{

    /**
     * Create columns for table
     * @param $columns
     * @return string
     */
    private function columns($columns)
    {
        $string = '';
        foreach ($columns as $key => $value) {
            if ($value['type'] == 'text' or $value['type'] == 'autoComplete' or $value['type'] == 'multiple'
                or $value['type'] == 'tags' or $value['type'] == 'date' or $value['type'] == 'rangeSlider'
                or $value['type'] == 'proImage' or $value['type'] == 'category' or $value['type'] == 'hidden'
                or $value['type'] == 'image' or $value['type'] == 'file' or $value['type'] == 'selectBox'
                or $value['type'] == 'map' or $value['type'] == 'hidden'
                ) {
                $string .= "`$key` varchar(255) NULL DEFAULT '',";
            } elseif($value['type'] == 'password') {
                $string .= "`$key` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NULL, ";
            } elseif($value['type'] == 'switcher' or $value['type'] == 'radio'
                    or $value['type'] == 'checkBox') {
                if($value['type'] == 'switcher' or $value['type'] == 'checkBox') {
                    $int = 'tinyint(1)';
                } elseif($value['type'] == 'radio') {
                    $int = 'tinyint(3)';
                } else {
                    $int = 'int(11)';
                }
                $string .= "`$key` $int NULL DEFAULT 0, ";
            } elseif($value['type'] == 'textarea') {
                $string .= "`$key` text NULL, ";
            } elseif($value['type'] == 'textEditor'){
                $string .= "`$key` longtext NULL, ";
            }
        }
        return $string;
    }

    /**
     * Create model properties
     * @param array $columns
     * @return mixed
     */
    private function columnsProperty($columns = [])
    {
        $string = '';
        foreach ($columns as $key => $value)
        {
            $string .= PHP_EOL . "    public $$key;" . PHP_EOL;
        }
        $string .= '//{columns}';
        return $string;
    }


    /**
     * Create model file
     * @param $table
     * @param $name
     * @param $columns
     */
    private function model($table, $name, $columns = [])
    {
        $columns = $this->columnsProperty($columns);
        $file = APP_PATH . 'app/Neotis/Core/Mvc/Model/Files/Model.php';
        $second = APP_PATH . "cli/model/$name.php";
        $final = APP_PATH . 'app/mvc/'.Url::$package.'/model/'.$name.'.php';
        $elements = file_get_contents($file);
        copy($file, $second);

        /** Config ini */
        $file = APP_PATH . 'app/config/config.ini';
        $ini = new Ini($file);
        $this->settings = $ini->settings;
        $search = array(
            '{date}',
            '{time}',
            '{project}',
            '{email}',
            '{name}',
            '{family}',
            '{model}',
            '{table}',
            '//{columns}'
        );
        $replace = array(
            date("Y-m-d"),
            date("g:i a"),
            $this->settings->programmer->project,
            $this->settings->programmer->email,
            $this->settings->programmer->name,
            $this->settings->programmer->family,
            $name,
            $table,
            $columns
        );
        $elements = str_ireplace($search, $replace, $elements);
        file_put_contents($second, $elements);
        rename($second, $final);
    }


    /**
     * Create new table
     * @param $db
     * @param $table
     * @param $model
     * @param $columns
     * @param $details
     * @param $models
     */
    public function create($db, $table, $model, $columns, $details, $models, $type){
        if(isset($details[$table]) or isset($models[$model])){die('exist');}
        $tables = $this->columns($columns);
        if($type == 'base') {
            $sql = "
            CREATE TABLE `$table` (
                  `id` int(11) NOT NULL,
                  `login_record` int(11) NOT NULL,
                  `selector` char(32) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  $tables
                  `status` tinyint(1) NOT NULL DEFAULT '0',
                  `deleted` tinyint(1) NOT NULL DEFAULT '0',
                  `ip` char(15) NOT NULL DEFAULT '0',
                  `timestamp` int(11) NOT NULL DEFAULT '0',
                  `useragent` varchar(255) DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          ";
        } else {
            $sql = "
            CREATE TABLE `$table` (
                  `id` int(11) NOT NULL,
                  `login_record` int(11) NOT NULL,
                  `selector` char(32) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  $tables
                  `relation` char(32) NOT NULL DEFAULT '0',
                  `status` tinyint(1) NOT NULL DEFAULT '0',
                  `deleted` tinyint(1) NOT NULL DEFAULT '0',
                  `ip` char(15) NOT NULL DEFAULT '0',
                  `timestamp` int(11) NOT NULL DEFAULT '0',
                  `useragent` varchar(255) DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          ";
        }
        //Set query to pdo
        $find = $db->prepare($sql);
        //Execute query
        $find->execute();
        $sql = "
        ALTER TABLE `$table`
                ADD PRIMARY KEY (`id`),
                ADD KEY `user_id` (`user_id`),
                ADD KEY `status` (`status`),
                ADD KEY `deleted` (`deleted`),
                ADD KEY `ip` (`ip`),
                ADD KEY `timestamp` (`timestamp`),
                ADD KEY `selector` (`selector`),
                ADD KEY `login_record` (`login_record`);
        ";
        //Set query to pdo
        $find = $db->prepare($sql);
        //Execute query
        $find->execute();
        $sql = "
          ALTER TABLE `$table`
          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ";
        //Set query to pdo
        $find = $db->prepare($sql);
        //Execute query
        $find->execute();

        //Create model
        $this->model($table, $model, $columns);
    }
}