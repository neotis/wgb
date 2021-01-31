<?php
/**
 * Created by PhpStorm.

 * Date: 10/30/2018
 * Time: 3:36 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql;

use Neotis\Core\Database\Pdo\Mysql\Manipulators\Columns;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Execute;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\FullJoin;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\LeftJoin;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Query;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Replace;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\RightJoin;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Table;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Values;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Where;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Join;
use Neotis\Core\Database\Pdo\Mysql\Manipulators\Cache;
use Neotis\Core\Mvc\Model;

class Eloquent
{
    use Where;
    use Columns;
    use Values;
    use Replace;
    use Execute;
    use Table;
    use Join;
    use LeftJoin;
    use RightJoin;
    use FullJoin;
    use Cache;
    use Query {
        bind as bindToQuery;
    }

    /**
     * Define base string from mysql query
     * @var string
     */
    protected $baseString = '';

    /**
     * Store object of current selected table
     * @var null
     */
    protected $thisObject = null;

    /**
     * Store final query string to execute
     * @var string
     */
    protected $final = '';

    /**
     * Type of generate values of query
     * @var string
     */
    protected $valuesStatus = 'insert';

    /**
     * Bind parameter
     * @var array
     */
    protected $bindValues = [];

    /**
     * Store find single record status
     * @var bool
     */
    protected $findStatus = false;

    /**
     * Store find multiple record status
     * @var bool
     */
    protected $findAllStatus = false;

    /**
     * Name of table for create
     * @var string
     */
    protected $tableName = false;

    /**
     * Name of mode for create table
     * @var string
     */
    protected $modelName = false;

    /**
     * Define status of query string
     * @var bool
     */
    protected $queryStatus = false;

    /**
     * Type of requested query
     * @var string
     */
    protected $queryType = 'find';

    /**
     * Status of fetch deleted file from database
     * @var bool
     */
    protected $widthDelete = false;

    /**
     * Name of query method
     * @var string
     */
    protected $queryMethod = 'find';

    /**
     * Store condition
     * @var array
     */
    protected $condition = [];

    /**
     * Where params for final find string
     * @var array
     */
    protected $whereParams = [];

    /**
     * Final where string for event purposes
     * @var string
     */
    protected $finalWhere = 'SELECT {columns} FROM {table} {innerJoin} {leftJoin} {rightJoin} {fullJoin} {where} {group} {order} {limit} {offset}';

    /**
     * Make end of execute model
     * @var bool
     */
    protected $end = false;

    /**
     * Define status of Record logs record
     * @var bool
     */
    protected $record = false;

    /**
     * Store record change reason
     * @var string
     */
    protected $recordReason = '';

    /**
     * Eloquent constructor.
     */
    public function __construct()
    {
        $this->tableName = Model::$table;
        $this->thisObject = Model::$obj;
    }

    /**
     * Generate where condition
     * @param $condition
     * @return $this
     */
    public function where($condition)
    {
        $this->simple($condition);
        return $this;
    }

    /**
     * Generate where condition with "OR"
     * @param $condition
     * @return $this
     */
    public function orWhere($condition)
    {
        $this->orSimple($condition);
        return $this;
    }

    /**
     * Inner join of clause
     * @param $table
     * @param $parameters
     * @return $this
     */
    public function join($table, $parameters)
    {
        $this->joinClause($table, $parameters);
        return $this;
    }

    /**
     * Left join of clause
     * @param $table
     * @param $parameters
     * @return $this
     */
    public function leftJoin($table, $parameters)
    {
        $this->leftJoinClause($table, $parameters);
        return $this;
    }

    /**
     * Right join of clause
     * @param $table
     * @param $parameters
     * @return $this
     */
    public function rightJoin($table, $parameters)
    {
        $this->rightJoinClause($table, $parameters);
        return $this;
    }

    /**
     * Full join of clause
     * @param $table
     * @param $parameters
     * @return $this
     */
    public function fullJoin($table, $parameters)
    {
        $this->fullJoinClause($table, $parameters);
        return $this;
    }

    /**
     * Add value for Insert, Update, Replace and other
     * @param array $fields
     * @return Eloquent
     */
    public function values(array $fields = [])
    {
        $this->allValues = $fields;

        return $this;
    }

    /**
     * Fetch deleted file from database
     * @return Eloquent
     */
    public function withDeleted()
    {
        $this->widthDelete = true;

        return $this;
    }

    /**
     * Select columns from query to display
     * @param array $list
     * @return Eloquent
     */
    public function columns(array $list = [])
    {
        $this->selectColumns($list);
        return $this;
    }

    /**
     * Select page to display records of table
     * @param int $number
     * @return Eloquent
     */
    public function page($number = 1)
    {
        $this->wPage($number);
        return $this;
    }

    /**
     * Define limitation of select records from table
     * @param int $number
     * @return Eloquent
     */
    public function limit($number = 1)
    {
        $this->wLimit($number);
        return $this;
    }

    /**
     * Bind parameter and value to sql query
     * @param array $array
     * @return $this
     */
    public function bind($array = [])
    {
        $this->bindToQuery($array);
        return $this;
    }

    /**
     * Define offset for start from specefic record
     * @param int $number
     * @return Eloquent
     */
    public function offset($number = 1)
    {
        $this->wOffset($number);
        return $this;
    }

    /**
     * Select records order by specefic column
     * @param string $column
     * @return Eloquent
     */
    public function order($column = '')
    {
        $this->wOrder($column);
        return $this;
    }

    /**
     * Select records of table group by specefic column
     * @param string $column
     * @return Eloquent
     */
    public function group($column = '')
    {
        $this->wGroup($column);
        return $this;
    }

    /**
     * Create cache file from selected data
     * @param $name
     * @param $time
     * @param bool $user
     * @param bool $group
     * @param bool $query
     * @return Eloquent
     */
    public function cache($time)
    {
        $this->makeCache($time);
        return $this;
    }

    /**
     * Find and return one record of selected table
     * @param string $type
     * @return bool
     */
    public function find($type = 'default')
    {
        if(!$this->queryStatus){
            $this->baseString = 'SELECT {columns} FROM {table} {innerJoin} {leftJoin} {rightJoin} {fullJoin} {where} {group} {order} {limit} {offset}';
        }
        $this->findStatus = true;

        $this->queryType = 'find';
        $this->queryMethod = 'find';

        return $this->execute($type);
    }

    /**
     * Find and return multi record of selected table
     * @param string $type
     * @return bool
     */
    public function findAll($type = 'default')
    {
        if(!$this->queryStatus){
            $this->baseString = 'SELECT {columns} FROM {table} {innerJoin} {leftJoin} {rightJoin} {fullJoin} {where} {group} {order} {limit} {offset}';
        }
        $this->findAllStatus = true;

        $this->queryType = 'find';
        $this->queryMethod = 'findAll';

        return $this->execute($type);
    }

    /**
     *  Insert information to selected table
     * @param string $type
     * @return bool
     */
    public function add($type = 'default')
    {
        $this->insertValues();

        $this->baseString = 'INSERT INTO {table} {values}';

        $this->queryType = 'add';
        $this->queryMethod = 'add';

        return $this->execute($type);
    }

    /**
     *  Insert information to selected table
     * @param string $type
     * @return bool
     */
    public function replace($type = 'default')
    {
        $this->insertValues();

        $this->valuesStatus = 'insert';
        $this->baseString = 'REPLACE INTO {table} {values}';

        $this->queryType = 'replace';
        $this->queryMethod = 'replace';

        return $this->execute($type);
    }

    /**
     * Update selected record from table
     * @param string $type
     * @return bool
     */
    public function update($type = 'default')
    {
        $this->allValues['modify'] = time();
        $this->updateValues();
        $this->baseString = 'UPDATE {table} SET {values} {where}';

        $this->queryType = 'update';
        $this->queryMethod = 'update';

        return $this->execute($type);
    }

    /**
     * Delete selected record from table
     * @param string $type
     * @return bool
     */
    public function delete($type = 'default')
    {
        $this->baseString = 'DELETE FROM {table} {where}';

        $this->queryType = 'deleted';
        $this->queryMethod = 'deleted';

        return $this->execute($type);
    }


    /**
     * Soft delete selected record from table
     * @param string $type
     * @return bool
     */
    public function softDelete($type = 'default')
    {
        $this->baseString = 'UPDATE {table} SET deleted = 1 {where}';

        $this->queryType = 'update';
        $this->queryMethod = 'softDelete';

        return $this->execute($type);
    }

    /**
     * Create new table
     * @param string $name
     * @param string $model
     * @param string $type
     * @return Eloquent
     */
    public function table($name, $model, $type = 'default')
    {
        $this->baseString = '{tableSql}';
        $this->tableName = $name;
        $this->modelName = $model;
        $this->createTable($name, $model, $type);
        return $this;
    }

    /**
     * @param $name
     * @param $table
     * @param $type
     * @param $limit
     * @param $after
     * @param bool $null
     * @param bool $index
     * @return $this
     */
    public function column($name, $table, $type, $limit, $after, $null = true, $index = false)
    {
        $this->baseString = '{columnSql}';
        $this->newColumn($name, $table, $type, $limit, $after, $null, $index);
        return $this;
    }

    /**
     * Recover deleted record with soft delete
     */
    public function undo()
    {
        $this->baseString = 'UPDATE {table} SET deleted = 0 {where}';
        return $this;
    }

    /**
     * Execute custom query
     * @param $sql
     * @return $this
     */
    public function query($sql)
    {
        $this->baseString = $sql;
        $this->queryStatus = true;
        return $this;
    }

    /**
     * Make end of update for sometime you want to use "Models Events"
     */
    public function end()
    {
        $this->end = true;
        return $this;
    }

    /**
     * Record logs change for selected table
     * @param string $reason
     * @return Eloquent
     */
    public function record($reason = '')
    {
        $this->recordReason = $reason;
        $this->record = true;
        return $this;
    }

    /**
     * Execute final query
     * @param string $type
     * @return bool
     */
    private function execute($type = 'default')
    {
        $this->replacer();
        return $this->_do($type);
    }

    /**
     * discharge properties
     */
    private function discharger()
    {
        $this->columns = ' * ';
        $this->params = [];
        $this->values = '';
        $this->where = '';
        $this->prefix = '';
        $this->page = 0;
        $this->limit = 0;
        $this->limitString = '';
        $this->order = '';
        $this->group = '';
        $this->offset = 0;
        $this->offsetString = '';
        $this->baseString = '';
        $this->final = '';
        $this->valuesStatus = 'insert';
        $this->bindValues = [];
        $this->findStatus = false;
        $this->findAllStatus = false;
        $this->tableName = false;
        $this->modelName = false;
        $this->queryStatus = false;
        $this->thisObject = null;
    }
}
