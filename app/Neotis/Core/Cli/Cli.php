<?php

/**
 * Created by PhpStorm.

 * Date: 11/14/2017
 * Time: 3:41 PM
 * Neotis framework
 */

namespace Neotis\Core\Cli;

use LucidFrame\Console\ConsoleTable;
use Neotis\Core\Neotis;

class Cli extends Neotis
{
    /**
     * Default namespace for run command
     * @var string
     */
    private $namespace = "Neotis\\Core\\Cli\\";

    /**
     * Get value from user
     * @param string $type
     * @param int $length
     * @return bool|string
     */
    protected function in($type = 'pure', $length = 255)
    {
        $command = fgets(STDIN, $length);
        $command = trim(preg_replace('/\s\s+/', ' ', $command));
        if($type == 'lower'){
            $command = strtolower($command);
        }elseif($type == 'ucfirst'){
            $command = ucfirst(strtolower($command));
        }
        return $command;
    }

    /**
     * Generate command class title for run selected class
     */
    public function command()
    {
        $value = $this->in();
        $value = strtolower($value);
        $value = ucfirst($value);
        $value = $this->namespace . $value;
        if(class_exists($value, true)){
            $command = new $value();
            $command->run();
        }else{
            $this->commandHelp();
            $this->inputTitle("Tell me your command");
            $this->command();
        }
    }

    /**
     * Display command title
     * @param $title
     */
    protected function inputTitle($title)
    {
        echo "$title: \n > ";
    }

    /**
     * Display help about commands
     */
    private function commandHelp()
    {
        $table = new ConsoleTable();
        $table
            ->setHeaders(array('Row', 'Command', 'Description'))
            ->addRow(array('1', 'Install', 'Install Neotis Framework'))
            ->addRow(array('2', 'Setup', 'Setup configuration of framework'))
            ->addRow(array('3', 'Package', 'Package manager and install package'))
            ->addRow(array('4', 'Controller', 'Controller manager, Create, Modify and delete controller'))
            ->display();
    }

    /**
     * Run cli
     */
    public function run()
    {
        $this->commandHelp();
        $this->inputTitle("Tell me your command");
        $this->command();
    }
}
