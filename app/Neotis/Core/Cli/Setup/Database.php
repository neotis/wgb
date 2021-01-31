<?php

/**
 * Setup framework
 * Created by PhpStorm.

 * Date: 11/14/2017
 * Time: 3:41 PM
 * Neotis framework
 */

namespace Neotis\Core\Cli\Setup;

use Neotis\Core\Cli\Cli;
use LucidFrame\Console\ConsoleTable;
use Neotis\Core\Services\Methods;

class Database extends Cli
{

    /**
     * Collect value for put in basic config file
     * @var array
     */
    static $values = [];

    /**
     * Create default values of basic config
     */
    private function defaultValues()
    {
        echo "+--------------------------------------------------------------+ \n";
        echo "Default database connection information (Press enter to use default value) \n";
        echo "+--------------------------------------------------------------+ \n";
        $this->inputTitle('Type of database (Default: mysql)');
        $type = $this->in();
        if (empty($type)) {
            $type = 'mysql';
        }
        self::$values['default']['type'] = $type;
        $this->inputTitle('Adapter of database (Default: pdo)');
        $adapter = $this->in();
        if (empty($adapter)) {
            $adapter = 'pdo';
        }
        self::$values['default']['adapter'] = $adapter;
        $this->inputTitle('Database connection host (Default: localhost)');
        $host = $this->in();
        if (empty($host)) {
            $host = 'localhost';
        }
        self::$values['default']['host'] = $host;
        $this->inputTitle('Database connection username (Default: root)');
        $username = $this->in();
        if (empty($username)) {
            $username = 'root';
        }
        self::$values['default']['username'] = $username;
        $this->inputTitle('Database connection password (Default: NULL)');
        $password = $this->in();
        if (empty($password)) {
            $password = '';
        }
        self::$values['default']['password'] = $password;
        $this->inputTitle('Database name (Default: NULL)');
        $db = $this->in();
        if (empty($db)) {
            $db = '';
        }
        self::$values['default']['dbName'] = $db;
        $this->inputTitle('Database connection charset (Default: NULL)');
        $charset = $this->in();
        if (empty($charset)) {
            $charset = '';
        }
        self::$values['default']['charset'] = $charset;
    }

    /**
     * Execute basic config value
     * @throws \Matomo\Ini\IniWritingException
     */
    public function run()
    {
        echo "\n";
        $this->defaultValues();
        $values = json_encode(self::$values, JSON_UNESCAPED_UNICODE);
        $path = APP_PATH . 'app/config/db/';
        if (!file_exists(APP_PATH . $path . 'connections.json')) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . 'connections.json', $values);
    }
}
