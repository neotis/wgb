<?php

/**
 * Neotis model rely
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 * @router class
 */

namespace Neotis\Core\Mvc;

use Neotis\Core\Database\Pdo\Mysql\Eloquent as MysqlPdo;
use Neotis\Core\Router\Router;

class Model extends MysqlPdo
{
    /**
     * List of table name and Alias name of relation model
     * @var array
     */
    public static $tables = [];

    /**
     * Store object of selected class
     * @var null
     */
    public static $obj = null;

    /**
     * Store type of database
     * @var string
     */
    public static $type = '';

    /**
     * Protected static properties area
     * @var string
     */
    public static $adapter = '';

    /**
     * Define host name
     * @var string
     */
    public static $host = '';

    /**
     * Define username of connection to database
     * @var string
     */
    public static $username = '';

    /**
     * Define password of connection to database
     * @var string
     */
    public static $password = '';

    /**
     * Define database name
     * @var string
     */
    public static $dbName = '';

    /**
     * Define charset of connection to database
     * @var string
     */
    public static $charset = '';

    /**
     * Store selected table name
     * @var string
     */
    public static $table = '';

    /**
     * Store unique key name
     * @var string
     */
    public static $key = '';

    /**
     * Store id number of selected record
     * @var int
     */
    public static $id = 0;

    /**
     * Store count of selected records
     * @var int
     */
    public static $count = 0;

    /**
     * Store count of pages
     * @var int
     */
    public static $pages = 0;

    /**
     * Store final eloquent object
     * @var object
     */
    public static $mysqlPdoEloquent = null;

    /**
     * Last insert id of record data to database
     * @var int
     */
    public static $lastId = 0;

    /**
     * Last insert selector string of record data to database
     * @var int
     */
    public static $lastSelector = 0;

    /**
     * Store error info from execute sql
     * @var array
     */
    public static $errors = [];

    /**
     * Insert values to database
     * @param $name
     * @return void
     */
    public static function setConfig($name)
    {
        self::$obj = (new static);
        $configFile = APP_PATH . 'app' . DS . 'config' . DS . 'db' . DS . 'connections.json';
        $config = file_get_contents($configFile);
        $config = json_decode($config, true);
        $config = $config[$name];
        if (method_exists(self::$obj, 'key')) {
            self::$key = self::$obj->key();
            self::$table = self::$obj->getSource();
        }
        foreach ($config as $key => $value) {
            self::${$key} = $value;
        }
    }

    /**
     * Create new connection to database
     * @param string $name
     * @return static | object
     */
    public static function connect($name = 'default')
    {
        self::setConfig($name);
        self::modelsTableName();
        if (strtolower(self::$type) == 'mysql' and strtolower(self::$adapter) == 'pdo') {
            if (!empty(self::$mysqlPdoEloquent)) {
                return self::$mysqlPdoEloquent;
            } else {
                return (new static);
            }
        } else {
            return self::$mysqlPdoEloquent;
        }
    }

    /**
     * Return and fetch real table names from model
     */
    private static function modelsTableName()
    {
        if (empty(self::$tables)) {
            $package = Router::getPackage();
            $packageConfig = Router::getPackagesConfigs()['packages'][$package];

            $modelsDir = APP_PATH . 'app/mvc/back-end/' . $package . '/models/';
            if (is_dir($modelsDir)) {
                $tables = scandir($modelsDir);
                foreach ($tables as $key => $value) {
                    $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                    if (!is_dir($modelsDir . $value) and $ext === 'php') {
                        $file = basename($value, ".php");
                        if ($file != 'BaseModel') {
                            self::$tables[$file] = (new $file())->getSource();
                        }
                    }
                }
            }
            $modelsDir = APP_PATH . 'app/mvc/back-end/base/models/';
            if (is_dir($modelsDir)) {
                $tables = scandir($modelsDir);
                foreach ($tables as $key => $value) {
                    $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                    if (!is_dir($modelsDir . $value) and $ext === 'php') {
                        $file = basename($value, ".php");
                        if ($file != 'BaseModel') {
                            self::$tables[$file] = (new $file())->getSource();
                        }
                    }
                }
            }

            if (isset($packageConfig['share_models']) and is_string($packageConfig['share_models'])) {
                $modelsDir = APP_PATH . 'app/mvc/back-end/' . $packageConfig['share_models'] . DS;
                if (is_dir($modelsDir)) {
                    $tables = scandir($modelsDir);
                    foreach ($tables as $key => $value) {
                        $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                        if (!is_dir($modelsDir . $value) and $ext === 'php') {
                            $file = basename($value, ".php");
                            if ($file != 'BaseModel') {
                                self::$tables[$file] = (new $file())->getSource();
                            }
                        }
                    }
                }
            } elseif (isset($packageConfig['share_models']) and is_array($packageConfig['share_models'])) {
                foreach ($packageConfig['share_models'] as $key => $value) {
                    $modelsDir = APP_PATH . 'app/mvc/back-end/' . $value . DS;
                    if (is_dir($modelsDir)) {
                        $tables = scandir($modelsDir);
                        foreach ($tables as $key => $value) {
                            $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                            if (!is_dir($modelsDir . $value) and $ext === 'php') {
                                $file = basename($value, ".php");
                                if ($file != 'BaseModel') {
                                    self::$tables[$file] = (new $file())->getSource();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
