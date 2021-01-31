<?php
/**
 * Created by PhpStorm.
 * Date: 10/28/2018
 * Time: 10:38 PM
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;


use Neotis\Core\Http\Request;
use Neotis\Core\Mvc\Model;
use Neotis\Core\Router\Router;
use Neotis\Core\Services\Methods;

trait Values
{
    /**
     * Store values query
     * @var string
     */
    protected $values = '';

    /**
     * Store insert values from user
     * @var array
     */
    protected $allValues = [];

    /**
     * Generate insert value in query
     */
    public function insertValues()
    {
        $prefix = $fields = $values = '';
        $fieldsValue = $this->allValues;
        if (!empty($fieldsValue)) {
            if (!isset($fieldsValue['selector'])) {
                $fieldsValue['selector'] = Methods::selector();
                Model::$lastSelector = $fieldsValue['selector'];
            }
            if (!isset($fieldsValue['ip'])) {
                $fieldsValue['ip'] = Request::getUserIp();
            }
            if (!isset($fieldsValue['user'])) {
                if ($this->tableName !== 'users') {
                    $fieldsValue['user'] = Router::getUserId();
                }
            }
            if (!isset($fieldsValue['timestamp'])) {
                $fieldsValue['timestamp'] = Request::getTimestamp();
            }
            if (!isset($fieldsValue['useragent'])) {
                $fieldsValue['useragent'] = Request::getUseragent();
            }
            if (isset($fieldsValue['modify'])) {
                unset($fieldsValue['modify']);
            }
            if (method_exists($this->thisObject, 'beforeInsert')) {
                $fieldsValue = $this->thisObject::{'beforeInsert'}($fieldsValue);
            }
            if (!empty($fieldsValue)) {
                foreach ($fieldsValue as $key => $value) {
                    $pCounter = $this->paramCount;
                    $pCounter++;
                    $this->paramCount = $pCounter;
                    $fields .= $prefix . '' . $key;
                    $values .= $prefix . ' :' . $key . $pCounter;
                    $this->params[':' . $key . $pCounter] = $value;
                    $prefix = ', ';
                }
            }
        }

        $this->allValues = $fieldsValue;

        $this->values = '(' . $fields . ') VALUES (' . $values . ')';
    }

    /**
     * Generate insert value in query
     */
    public function updateValues()
    {
        $prefix = $columns = '';
        $fields = $this->allValues;
        if (!empty($fields)) {
            $firstKey = array_key_first($fields);
            if(isset($fields[$firstKey]) and !is_array($fields[$firstKey])){
                if (method_exists($this->thisObject, 'beforeUpdate')) {
                    $fields = $this->thisObject::{'beforeUpdate'}($fields);
                }
                foreach ($fields as $key => $value) {
                    $pCounter = $this->paramCount;
                    $pCounter++;
                    $this->paramCount = $pCounter;
                    $columns .= $prefix . $key . ' = ' . ' :' . $key . $pCounter;
                    $this->params[':' . $key . $pCounter] = $value;
                    $prefix = ', ';
                }
            }else{
                if (method_exists($this->thisObject, 'beforeUpdate')) {
                    $fields = $this->thisObject::{'beforeUpdate'}($fields);
                }
                foreach ($fields as $key => $value) {
                    if($key === 'modify'){
                        $columns .= $prefix . 'modify = ' . $value;
                        $prefix = ', ';
                    }else{
                        $columns .= $prefix . $value[0] . ' = ' . $value[1];
                        $prefix = ', ';
                    }
                }
            }
        }

        $this->values = $columns;
    }
}
