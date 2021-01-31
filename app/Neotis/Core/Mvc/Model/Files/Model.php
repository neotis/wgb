<?php

/**
 * Created by PhpStorm.
 * User: {email}
 * Date: {date}
 * Programmer: {name} {family}
 * Email: {email}
 * Time: {time}
 * Neotis framework
 * Project: {project}
 */

class {model} extends BaseModel
{
    /**
     * Return primary key column
     * @return string
     */
    public function key(){
        return '{key}';
    }

    /**
     * Return real name of table on database
     * @return string
     */
    public function getSource()
    {
        return '{table}';
    }
}