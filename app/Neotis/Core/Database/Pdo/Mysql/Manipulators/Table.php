<?php
/**
 * Created by PhpStorm.
 * Date: 1/3/2019
 * Time: 1:14 AM
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;

use Neotis\Core\Mvc\Model;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

trait Table
{
    /**
     * Sql string for create table
     * @var string
     */
    public $tableSql = '';

    /**
     * Sql string og create column for table
     * @var string
     */
    public $columnSql = '';

    /**
     * Create new table
     * @param $table
     * @param $model
     * @param $type
     */
    public function createTable($table, $model, $type)
    {
        $this->tableSql = "
                  CREATE TABLE IF NOT EXISTS `$table` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `selector` char(32) COLLATE utf8_bin NOT NULL DEFAULT '0',
                    `user_id` bigint(20) NOT NULL DEFAULT '0',
                    `deleted` tinyint(1) NOT NULL DEFAULT '0',
                    `ip` char(15) COLLATE utf8_bin DEFAULT NULL,
                    `timestamp` bigint(20) NOT NULL DEFAULT '0',
                    `modify` bigint(20) NOT NULL DEFAULT '0',
                    `useragent` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `Deleted` (`deleted`),
                    KEY `UserId` (`user_id`),
                    KEY `Selector` (`selector`),
                    KEY `Ip` (`ip`),
                    KEY `Timestamp` (`timestamp`),
                    KEY `Modify` (`modify`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
          ";
        $this->createModel($table, $model, $type);
    }

    /**
     * Create new model with custom table name
     * @param $table
     * @param $model
     * @param $type
     */
    private function createModel($table, $model, $type)
    {
        $email = 'shahin.ataei.1990@gmail.com';
        $date = date("Y-m-d",time());
        $name = 'Shahin';
        $family = 'Ataei';
        $time = date("h:i:sa");
        $project = 'Neotis framework';
        $key = 'id';
        $package = Router::getPackage();
        $src = APP_PATH . 'app' . DS . 'Neotis' . DS . 'Core' . DS . 'Mvc' . DS . 'Model' . DS . 'Files' . DS . 'Model.php';
        if ($type == 'base') {
            $modelName = $type;
        } else {
            $modelName = $package;
        }
        $dest = APP_PATH . 'app' . DS . 'mvc' . DS . $modelName . DS . 'models' . DS . $model . '.php';
        copy($src, $dest);
        $file = file_get_contents($dest);
        $search = ['{email}', '{date}', '{name}', '{family}', '{time}', '{project}', '{model}', '{key}', '{table}'];
        $replace = [$email, $date, $name, $family, $time, $project, $model, $key, $table];
        $file = str_ireplace($search, $replace, $file);
        file_put_contents($dest, $file);
    }

    /**
     * Create new column
     * @param $name
     * @param $type
     * @param $table
     * @param $limit
     * @param $after
     * @param bool $null
     * @param bool $index
     */
    public function newColumn($name, $table, $type, $limit, $after, $null = true, $index = false)
    {
        $tables = Model::$tables;
        $table = $tables[$table];
        if($null){
            $null = 'NULL';
        }else{
            $null = '';
        }
        $this->columnSql = "ALTER TABLE `$table` ADD `$name` $type ($limit) $null AFTER `$after`;";
        if($index){
            $this->columnSql .= "ALTER TABLE `$table` ADD INDEX(`$name`);";
        }
    }
}
