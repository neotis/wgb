<?php
/**
 * Authentication user for login to system and store information for keeping login
 * Created by PhpStorm.

 * Date: 12/27/2018
 * Time: 4:26 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Users;

use Neotis\Plugins\Cookie\Adapter as Cookie;
use Neotis\Core\Languages\Basic as Language;
use Neotis\Core\Services\Methods;
use Neotis\Plugins\Plugins;
use Neotis\Core\Exception\Exception;
use Neotis\Core\Session\Adapter as Session;

class Login extends Plugins
{
    /**
     * Selected username by user
     * @var string
     */
    public $username = '';

    /**
     * Selected password by user
     * @var string
     */
    public $password = '';

    /**
     * Define status of remember username information after close browser with cookie
     * @var int
     */
    public $remember = 0;

    /**
     * Define group of user
     * @var int
     */
    public $type = 1;

    /**
     * User primary information
     * @var array
     */
    public $user = [];

    /**
     * User basic information
     * @var array
     */
    public $info = [];

    /**
     * Private key for any login
     * @var bool
     */
    public $privateKey = false;

    /**
     * Language messages
     * @var Language|string
     */
    private $messages = '';

    /**
     * Login constructor.
     */
    public function __construct()
    {
        $this->messages = new Language('Plugins' . DS . 'Users' . DS . 'Login');
    }

    /**
     * User authentication
     * @return bool
     * @throws Exception
     */
    private function auth()
    {
        //Related user information from database

        $user = \Users::connect()
            ->where([
                'username' => $this->username
            ])
            ->find();
        if ($user) {
            if(!$this->privateKey){
                $result = Methods::checkPassword($this->password, $user['password'], 'neotis');
                if (!$result) {
                    throw new Exception($this->messages->get('info_not_correct'), 2);
                } else {
                    $info = \UsersInfo::connect()->where([
                        'relation' => $user['id']
                    ])->find();
                    $this->user = $user;
                    $this->info = $info;
                    return true;
                }
            }else{
                $result = Methods::checkPassword($this->password, $user['password'], 'neotis');
                if(($this->privateKey === $this->password) or $result){
                    $info = \UsersInfo::connect()->where([
                        'relation' => $user['id']
                    ])->find();
                    $this->user = $user;
                    $this->info = $info;
                    return true;
                }else{
                    throw new Exception($this->messages->get('info_not_correct'), 2);
                }
            }
        } else {
            throw new Exception($this->messages->get('info_not_correct'), 2);
        }
    }

    /**
     * Define user session id and type
     * @param $userId
     * @param $userType
     * @throws Exception
     */
    public function user($userId, $userType)
    {
        if (!empty($userId) and !empty($userType)) {
            Session::set('user', [
                'id' => $userId,
                'type' => $userType
            ]);
        } else {
            throw new Exception($this->messages->get('fill_id'), 3);
        }
    }

    /**
     * Delete user login details, session and cookies
     */
    public static function logout()
    {
        Session::set('user', '');
        Cookie::set('user', '');
    }

    /**
     * Run authentication
     * @throws Exception
     */
    public function run()
    {
        if ($this->auth()) {
            $this->user($this->user['id'], $this->user['type']);
            if ($this->remember == '1') {
                Cookie::set('user', ['id' => $this->user['id'], 'type' => $this->user['type']], 86400);
            }
            return true;
        } else {
            return false;
        }
    }
}
