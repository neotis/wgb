<?php
/**
 * Created by PhpStorm.

 * Date: 10/28/2018
 * Time: 1:04 PM
 * Neotis framework
 */

namespace Neotis\Core\Database\Pdo\Mysql\Manipulators;


trait Columns
{
    /**
     * Store columns for select from table
     * @var string
     */
    protected $columns = ' * ';

    /**
     * Select columns of table
     * @param array $list
     */
    public function selectColumns(array $list = [])
    {
        if (!empty($list)) {
            $prefix = $columns = '';
            foreach ($list as $key => $value)
            {
                if(is_array($value)){//If selected column have nickname
                    foreach($value as $iKey => $iValue){
                        $columns .= $prefix . $iKey . ' as ' . $iValue;
                        $prefix = ', ';
                    }
                }else{
                    $columns .= $prefix . $value;
                    $prefix = ', ';
                }
            }
            if (empty($columns)) {//If select all column
                $columns = '*';
            }
        } else {
            $columns = '*';
        }
        $this->columns = $columns;
    }
}
