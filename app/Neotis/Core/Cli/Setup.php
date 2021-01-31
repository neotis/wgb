<?php

/**
 * Setup framework
 * Created by PhpStorm.

 * Date: 11/14/2017
 * Time: 3:41 PM
 * Neotis framework
 */

namespace Neotis\Core\Cli;

use Neotis\Core\Cli\Setup\Config;
use Neotis\Core\Cli\Setup\Database;
use LucidFrame\Console\ConsoleTable;

class Setup extends Cli
{
    /**
     * Run install class and application for Neotis Framework
     * @throws \Matomo\Ini\IniWritingException
     */
    public function run()
    {
        $table = new ConsoleTable();
        $table
            ->setHeaders(array('Row', 'Command', 'Description'))
            ->addRow(array('1', 'Config', 'Basic config of framework'))
            ->addRow(array('2', 'Database', 'Database connection information'))
            ->display();
        $this->inputTitle('Tell me your command');

        $step = $this->in();
        if ($step === '1') {
            (new Config())->run();
        } elseif ($step === '2') {
            (new Database())->run();
        }
    }
}
