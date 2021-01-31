<?php
/**
 * Fetch user information
 * Created by PhpStorm.

 * Date: 12/27/2018
 * Time: 4:26 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Users;

use Neotis\Core\Services\Methods;
use Neotis\Plugins\Plugins;

class Info extends Plugins
{
    /**
     * Store tire class object
     * @var
     */
    private $connection;

    /**
     * Store result array of execute query
     * @var array
     */
    private $result = [];

    /**
     * Type of result and request to fetch information
     * @var string
     */
    private $resultType = 'find';

    /**
     * Card constructor.
     */
    public function __construct()
    {
        $this->connection = \Users::connect();
        $this->connection = $this->connection->leftJoin('UsersInfo', [
            ['UsersInfo.relation', '=', 'Users.id', 'column']
        ]);
        $this->connection = $this->connection->leftJoin('UsersGroups', [
            ['UsersGroups.id', '=', 'Users.type', 'column']
        ]);
        $this->connection = $this->connection->columns([
            ['Users.id' => 'id'],
            ['Users.type' => 'type'],
            ['Users.selector' => 'selector'],
            ['Users.username' => 'username'],
            ['UsersInfo.first_name' => 'first_name'],
            ['UsersInfo.last_name' => 'last_name'],
            ['UsersInfo.mobile' => 'mobile'],
            ['UsersInfo.phone' => 'phone'],
            ['UsersInfo.postal' => 'postal'],
            ['UsersInfo.email' => 'email'],
            ['UsersInfo.melli' => 'melli'],
            ['UsersInfo.country' => 'country'],
            ['UsersInfo.state' => 'state'],
            ['UsersInfo.city' => 'city'],
            ['UsersInfo.area' => 'area'],
            ['UsersInfo.address' => 'address'],
            ['UsersInfo.relation' => 'relation'],
            ['UsersGroups.name' => 'type_name']
        ]);
        $this->connection = $this->connection->where([
            ['Users.id', '!=', '1'],
            ['Users.id', '!=', '2']
        ]);
    }

    /**
     * Return list of selected user
     * @return Info
     */
    public function find()
    {
        $this->result = $this->connection->find();
        if (!empty($this->result)) {
            $this->resultType = 'find';
        }
        return $this;
    }

    /**
     * Return list of selected user
     * @return Info
     */
    public function findAll()
    {
        $this->connection = $this->connection->group('Users.id');
        $this->result = $this->connection->findAll();
        $this->resultType = 'findAll';
        return $this;
    }


    /**
     * Return result as array object
     * @return array
     */
    public function asArray()
    {
        return $this->result;
    }

    /**
     * Return result as json object
     * @return false|string
     */
    public function asJson()
    {
        return Methods::jsonOut($this->result);
    }

    /**
     * Limit row by number
     * @param $number
     * @return Info
     */
    public function limit($number)
    {
        $this->connection = $this->connection->limit($number);
        return $this;
    }

    /**
     * Select by user id
     * @param $id
     * @return Info
     */
    public function user($id)
    {
        $this->connection = $this->connection->orWhere([
            ['Users.id', '=', $id],
            ['Users.username', '=', $id]
        ]);
        return $this;
    }

    /**
     * Select by user id
     * @param $id
     * @return Info
     */
    public function type($id)
    {
        $this->connection = $this->connection->orWhere([
            ['Users.type', '=', $id],
        ]);
        return $this;
    }

    /**
     * Search in users with search keyword
     * @param $title
     * @return Info
     */
    public function bySearch($title)
    {
        if (!empty($title) and $title != false) {
            $this->connection = $this->connection->orWhere([
                ['Users.username', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.first_name', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.last_name', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.country', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.state', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.city', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.area', 'LIKE', '%' . $title . '%'],
                ['UsersInfo.mobile', '=', $title],
                ['UsersInfo.phone', '=', $title],
                ['UsersInfo.number', '=', $title],
                ['UsersInfo.email', '=', $title]
            ]);
        }
        return $this;
    }
}
