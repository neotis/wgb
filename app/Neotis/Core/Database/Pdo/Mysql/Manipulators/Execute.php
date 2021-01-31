<?php
/**
 * Created by PhpStorm.

 * Date: 10/30/2018
 * Time: 3:06 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;

use Neotis\Core\Mvc\Model;
use Neotis\Core\Services\Methods;


trait Execute
{
    /**
     * Store values for bind parameters to query string
     * @var array
     */
    public $params = [];

    /**
     * Store connections information
     * @var array
     */
    private static $connections = [];

    /**
     * Store PDO object with work in database
     * @var object
     */
    private static $conn;

    /**
     * Counter of parameter maker
     * @var int
     */
    public $paramCount = 0;

    /**
     * Make connection with PDO adapter in MySql
     */
    private function connector()
    {
        $serverName = Model::$host;
        $dbName = Model::$dbName;
        $username = Model::$username;
        $password = Model::$password;

        $id = md5($serverName . $dbName . $username . $password);

        if (!isset(self::$connections[$id])) {
            //Make connection with PDO
            self::$connections[$id] = new \PDO("mysql:host=$serverName;dbname=$dbName;charset=utf8", $username, $password);
            self::$connections[$id]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // set the PDO error mode to exception
        }

        self::$conn = self::$connections[$id];
    }

    /**
     * Bind value and parameters to query string for execute
     * @param $stmt
     * @return mixed
     */
    private function bindParam($stmt)
    {
        foreach ($this->params as $key => &$value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_STR);
        }
        return $stmt;
    }

    /**
     * Bind value and parameters to query string for execute
     * @param $stmt
     * @return mixed
     */
    private function bindParamWhereFinal($stmt)
    {
        foreach ($this->whereParams as $key => &$value) {
            $stmt->bindValue($key, $value, \PDO::PARAM_STR);
        }
        return $stmt;
    }

    /**
     * Record changes history
     * @param $table
     * @param $result
     * @param $values
     */
    private function recordLogs($table, $result, $values)
    {
        if ($result) {
            foreach ($result as $key => $value) {
                $previous = $table::connect()->where(['id' => $value['id']])->find();
                if ($previous) {
                    $previous = Methods::jsonOut($previous);
                    $values = Methods::jsonOut($values);
                    \LogsChanges::connect()->values([
                        '_table' => $this->tableName,
                        '_id' => $value['id'],
                        '_before' => $previous,
                        '_after' => $values,
                        'reason' => $this->recordReason
                    ])->add();
                }
            }
        }
    }

    /**
     * Do execute query string with PDO
     * @param $type
     * @return bool
     */
    protected function _do($type)
    {
        if ((($this->findStatus or $this->findAllStatus) and $type == 'default') || ($type == 'one' || $type == 'all')) {

            if ($this->cacheStatus) {
                $values = array_merge($this->whereParams, $this->params);
                $this->checkCache($this->final, $values);
                if ($this->cacheResultStatus) {
                    $error[0] = '00000';
                    Model::$errors[0] = '00000';
                    Model::$lastId = 0;
                } else {
                    $this->connector();

                    // prepare sql and bind parameters
                    $stmt = self::$conn->prepare($this->final);

                    $stmt = $this->bindParam($stmt);
                    $stmt->execute();

                    $error = $stmt->errorInfo();

                    Model::$errors = $error;
                    Model::$lastId = self::$conn->lastInsertId();
                }
            } else {
                $this->connector();

                // prepare sql and bind parameters
                $stmt = self::$conn->prepare($this->final);

                $stmt = $this->bindParam($stmt);
                $stmt->execute();

                $error = $stmt->errorInfo();

                Model::$errors = $error;
                Model::$lastId = self::$conn->lastInsertId();
            }
        } else {
            $this->connector();

            // prepare sql and bind parameters
            $stmt = self::$conn->prepare($this->final);

            $stmt = $this->bindParam($stmt);
            $stmt->execute();
            $error = $stmt->errorInfo();

            Model::$errors = $error;
            Model::$lastId = self::$conn->lastInsertId();
        }

        if ($this->queryMethod == 'add') {
            $lastId = self::$conn->lastInsertId();
            if (method_exists($this->thisObject, 'afterInsert') and (!$this->end)) {
                $this->thisObject::{'afterInsert'}(Model::$lastId);
            }
        } elseif ($this->queryMethod == 'update') {
            if ($this->record) {
                // prepare sql and bind parameters
                $stmt = self::$conn->prepare($this->finalWhere);

                $stmt = $this->bindParamWhereFinal($stmt);

                $stmt->execute();
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if (empty($result)) {
                    $result = false;
                }
                $this->recordLogs($this->thisObject, $result, $this->allValues);
            }

            if (method_exists($this->thisObject, 'afterUpdate') and (!$this->end)) {
                // prepare sql and bind parameters
                $stmt = self::$conn->prepare($this->finalWhere);

                $stmt = $this->bindParamWhereFinal($stmt);

                $stmt->execute();
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if (empty($result)) {
                    $result = false;
                }
                $this->thisObject::{'afterUpdate'}($result);
            }
        } elseif ($this->queryMethod == 'delete') {
            if (method_exists($this->thisObject, 'afterDelete') and (!$this->end)) {
                $this->thisObject::{'afterDelete'}(Model::$lastId);
            }
        } elseif ($this->queryMethod == 'softDelete') {
            if (method_exists($this->thisObject, 'afterSoftDelete') and (!$this->end)) {
                $this->thisObject::{'afterSoftDelete'}(Model::$lastId);
            }
        }

        $result = true;
        if ($error[0] === '00000') {
            if ($this->cacheStatus) {
                if ($this->cacheResultStatus) {
                    $result = $this->cacheContent;
                } else {
                    //Return one record of table
                    if (($this->findStatus and $type == 'default') || $type == 'one') {
                        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                        if (empty($result)) {
                            $result = false;
                        }
                    }

                    //Return multiple record of table
                    if (($this->findAllStatus and $type == 'default') || $type == 'all') {
                        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        if (empty($result)) {
                            $result = false;
                        }
                    }

                    $values = array_merge($this->whereParams, $this->params);

                    $this->generateCache($this->final, $values, $result);
                }
            } else {
                //Return one record of table
                if (($this->findStatus and $type == 'default') || $type == 'one') {
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                    if (empty($result)) {
                        $result = false;
                    }
                }

                //Return multiple record of table
                if (($this->findAllStatus and $type == 'default') || $type == 'all') {
                    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                    if (empty($result)) {
                        $result = false;
                    }
                }
            }
        } else {
            $result = false;
        }
        if ($result !== false) {
            if ($this->queryType == 'add') {
                $result = [
                    'param' => $stmt,
                    'insertId' => $lastId,
                    'db' => self::$conn,
                    'result' => $result,
                    'selector' => $this->allValues['selector'],
                    'id' => $lastId
                ];
            }
        }
        $this->discharger();
        return $result;
    }
}
