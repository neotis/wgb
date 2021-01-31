<?php


namespace Neotis\Plugins\Session;


use Neotis\Core\Session\Adapter;

class Handler implements \SessionHandlerInterface
{
    /**
     * Status of start session
     * @var bool
     */
    private static $start = false;


    public function open($savePath, $sessionName)
    {
        if (!self::$start) {
            self::$start = true;
            return true;
        }
        return false;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        if (Adapter::$token) {
            $id = Adapter::$token;
        }

        $data = \Sessions::connect()
            ->where([
                'id' => $id
            ])
            ->find();
        if (!empty($data)) {
            return $data['data'];
        } else {
            return '';
        }
    }

    public function write($id, $data)
    {
        if (Adapter::$token and !Adapter::$reIdStatus) {
            $id = Adapter::$token;
        }

        $replace = \Sessions::connect()
            ->values([
                'data' => $data,
                'id' => $id
            ])
            ->replace();

        Adapter::$reIdStatus = false;
        if ($replace) {
            return true;
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        if (Adapter::$token) {
            $id = Adapter::$token;
        }
        \Sessions::connect()
            ->where([
                'id' => $id
            ])
            ->delete();
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }
}