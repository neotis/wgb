<?php

/**
 * Setup framework
 * Created by PhpStorm.

 * Date: 11/14/2017
 * Time: 3:41 PM
 * Neotis framework
 */

namespace Neotis\Core\Cli\Setup;

use Matomo\Ini\IniWriter;
use Neotis\Core\Cli\Cli;
use LucidFrame\Console\ConsoleTable;

class Config extends Cli
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
        echo "Default value of basic config (Press enter to use default value) \n";
        echo "+--------------------------------------------------------------+ \n";
        $this->inputTitle('Time zone (Default: Canada/Central)');
        $time = $this->in();
        if (empty($time)) {
            $time = 'Canada/Central';
        }
        self::$values['default']['timeZone'] = $time;
        $this->inputTitle('Developer mode (Default: true)');
        $developer = $this->in();
        if (empty($developer)) {
            $developer = true;
        }
        self::$values['default']['developer'] = $developer;
    }

    /**
     * Create optional values of basic config
     */
    private function optionalValues()
    {
        echo "\n+-------------------------------+ \n";
        echo "Optional value of basic config \n";
        echo "+-------------------------------+ \n";
        $this->inputTitle('Password hash value (Default: neotis)');
        $hashPassword = $this->in();
        if (empty($hashPassword)) {
            $hashPassword = 'neotis';
        }
        self::$values['optional']['password_hash'] = $hashPassword;
    }

    /**
     * Create neotis account values of basic config
     */
    private function neotisAccountValues()
    {
        echo "\n+-------------------------------------+ \n";
        echo "Neotis account value of basic config \n";
        echo "+-------------------------------------+ \n";
        $this->inputTitle('Server address for connect to Neotis source (Default: https://neotis.co/)');
        $server = $this->in();
        if (empty($server)) {
            $server = 'neotis';
        }
        self::$values['neotis_account']['server'] = $server;
        $this->inputTitle('Server key for connect to Neotis source (Default: NULL)');
        $key = $this->in();
        if (empty($key)) {
            $key = '';
        }
        self::$values['neotis_account']['key'] = $key;
        $this->inputTitle('Name of project (Default: NULL)');
        $key = $this->in();
        if (empty($key)) {
            $key = '';
        }
        self::$values['neotis_account']['project'] = $key;
    }

    /**
     * Execute basic config value
     * @throws \Matomo\Ini\IniWritingException
     */
    public function run()
    {
        echo "\n";
        $this->defaultValues();
        $this->optionalValues();
        $this->neotisAccountValues();
        $writer = new IniWriter();

        $writer->writeToFile(APP_PATH . 'app' . DS . 'Config' . DS . 'config.ini', self::$values);
    }
}
