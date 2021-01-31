<?php

/**
 * Auto load project
 * Created by PhpStorm.

 * Date: 7/8/2017
 * Time: 11:10 AM
 * Neotis framework
 */

spl_autoload_register(function ($class) {
    if (
        $class != 'Neotis\Core\Events\Manager' and
        $class != 'Neotis\Core\Neotis' and
        $class != 'Neotis\Interfaces\Core\Events\Manager' and
        $class != 'Neotis\Core\Services\Methods' and
        $class != 'Neotis\Core\Events\Jobs' and
        $class != 'Neotis\Interfaces\Core\Events\Jobs' and
        $class != 'Neotis\Core\Events\ToDo' and
        $class != 'Neotis\Interfaces\Core\Events\ToDo'
    ) {
        $namespace = \Neotis\Core\Events\Jobs::$namespaces;
        if (isset($namespace[$class])) {
            $class = $namespace[$class];
        }
    }
    // separators with directory separators in the relative class name, append
    // with .php
    $file = APP_PATH . 'app' . DS . str_replace('\\', DS, $class) . '.php';
    if(!isset(self::$requireHistory[$file])){
        self::$requireHistory[$file] = true;

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
            return;
        }

        $name = str_ireplace(['Components\\', '\\'], ['', DS], $class);
        $name = explode(DS, $name);
        if (isset($name[1])) {
            $baseName = $name[1];
            $name[1] = 'components';
            $name[2] = $baseName;
            $name = implode(DS, $name);

            $file = APP_PATH . 'app' . DS . 'mvc' . DS . 'back-end' . DS . $name . '.php';
            // if the file exists, require it
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
