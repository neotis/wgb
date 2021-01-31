<?php
/**
 * Manipulate where clause
 * Created by PhpStorm.

 * Date: 10/11/2018
 * Time: 2:00 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;

use Neotis\Core\Mvc\Model;

trait Where
{
    /**
     * Store where condition string
     * @var string
     */
    protected $where = '';

    /**
     * Store where prefix
     * @var string
     */
    protected $prefix = '';

    /**
     * Store number of page
     * @var int
     */
    protected $page = 0;

    /**
     * Store number of limitation select records
     * @var int
     */
    protected $limit = 0;
    /**
     * Store number of limitation select records string
     * @var int
     */
    private $limitString = '';

    /**
     * Store order by string
     * @var string
     */
    protected $order = '';

    /**
     * Store group by string
     * @var string
     */
    protected $group = '';

    /**
     * Store offset to start record
     * @var int
     */
    protected $offset = 0;

    /**
     * Store offset string to start record
     * @var string
     */
    protected $offsetString = '';

    /**
     * Generate where condition
     * @param $condition
     * @return string
     */
    private function manufacturer($condition, $bPrefix = ' AND ')
    {
        $where = $prefix = '';
        if (isset($condition['OR'])) {//If condition is search by field = value by "AND" prefix
            foreach ($condition['OR'] as $key => $value) {
                $pCounter = $this->paramCount;
                $pCounter++;
                $this->paramCount = $pCounter;
                if (is_array($value)) {
                    $where .= $prefix . $value[0] . ' ' . $value[1] . ' :' . $key . $pCounter;
                    $this->params[':' . $key . $pCounter] = $value[2];
                    $this->whereParams[':' . $key . $pCounter] = $value[2];
                    $prefix = $bPrefix;
                } else {
                    $where .= $prefix . $key . ' = ' . ' :' . $key . $pCounter;
                    $this->params[':' . $key . $pCounter] = $value;
                    $this->whereParams[':' . $key . $pCounter] = $value;
                    $prefix = $bPrefix;
                }
            }
        } elseif (is_array($condition) and isset($condition[0]) and is_array($condition[0])) {//If the conditional instruction contains custom comparative operators
            foreach ($condition as $key => $value) {
                $pCounter = $this->paramCount;
                $pCounter++;
                $this->paramCount = $pCounter;
                $where .= $prefix . $value[0] . ' ' . $value[1] . ' :' . $key . $pCounter;
                $this->params[':' . $key . $pCounter] = $value[2];
                $this->whereParams[':' . $key . $pCounter] = $value[2];
                $prefix = $bPrefix;
            }
        } elseif (is_array($condition)) {//If condition is search by field "custom" value by "AND" prefix
            foreach ($condition as $key => $value) {
                $pCounter = $this->paramCount;
                $pCounter++;
                $this->paramCount = $pCounter;
                $where .= $prefix . $key . ' = ' . ' :' . $key . $pCounter;
                $this->params[':' . $key . $pCounter] = $value;
                $this->whereParams[':' . $key . $pCounter] = $value;
                $prefix = $bPrefix;
            }
        } elseif (is_int($condition) or (is_string($condition) and strlen($condition) >= 6) or is_numeric($condition)) {//If condition search by record ID
            if (is_string($condition) and !is_numeric($condition)) {
                $where = $this->tableName . ".selector = '$condition'";
            }else{
                $where = $this->tableName . "." . Model::$key . ' = ' . "'$condition'";
            }
            $where = ' (' . $where . ') ';
        }
        if ($this->widthDelete) {
            return $where;
        } else {
            return $where . ' AND ' . $this->tableName . '.deleted = 0';
        }
    }

    /**
     * Run where generator and generate where condition with "AND"
     * @param $condition
     * @return string
     */
    public function simple($condition)
    {
        $where = $this->manufacturer($condition);
        if (!empty($this->prefix)) {
            $this->prefix = ' AND ';
        }

        $this->where .= $this->prefix . '(' . $where . ')';
        $this->prefix = ' AND ';
        return $this;
    }

    /**
     * Run where generator and generate where condition with "OR"
     * @param $condition
     * @return $this
     */
    public function orSimple($condition)
    {
        $where = $this->manufacturer($condition, ' OR ');
        if (!empty($this->prefix)) {
            $this->prefix = ' AND ';
        }
        $this->where .= $this->prefix . ' (' . $where . ')';
        $this->prefix = ' AND ';
        return $this;
    }

    /**
     * Select page to display records of table
     * @param int $number
     */
    public function wPage($number = 1)
    {
        $this->offset = ($this->limit * $number) - $this->limit;
        $this->offsetString = 'OFFSET ' . $this->offset;
    }

    /**
     * Define limitation of select records from table
     * @param int $number
     */
    public function wLimit($number = 1)
    {
        $this->limit = $number;
        $this->limitString = 'LIMIT ' . $this->limit;
    }

    /**
     * Define offset for start from specefic record
     * @param int $number
     */
    public function wOffset($number = 1)
    {
        $this->offset = $number;
        $this->offsetString = 'OFFSET ' . $this->offset;
    }

    /**
     * Select records order by specefic column
     * @param string $column
     */
    public function wOrder($column = '')
    {
        if (empty($this->order)) {
            $this->order = 'ORDER BY ' . $column;
        } else {
            $this->order .= ', ' . $column;
        }
    }

    /**
     * Select records of table group by specefic column
     * @param string $column
     */
    public function wGroup($column = '')
    {
        $this->group = 'GROUP BY ' . $column;
    }
}
