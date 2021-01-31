<?php
/**
 * Register new user
 * Created by PhpStorm.

 * Date: 12/27/2018
 * Time: 4:26 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Users;

use Neotis\Core\Cookie\Adapter as Cookie;
use Neotis\Core\Languages\Basic as Language;
use Neotis\Core\Services\Methods;
use Neotis\Plugins\Plugins;
use Neotis\Core\Exception\Exception;
use Neotis\Core\Session\Adapter as Session;

class Register extends Plugins
{
    /**
     * Store messages class
     * @var array
     */
    public $messages = [];

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
     * Define group of user
     * @var int
     */
    public $type = 1;

    /**
     * User information
     * @var array
     */
    public $user = [];

    /**
     * Store errors message of registration
     * @var array
     */
    public $errors = [];

    /**
     * Store status of registration
     * @var bool
     */
    public $status = true;

    /**
     * Register constructor.
     */
    public function __construct()
    {
        $this->messages = new Language('Plugins' . DS . 'Users' . DS . 'Register');
    }

    /**
     * Register user to database
     * @throws Exception
     */
    public function register()
    {
        //Related user information from database
        $user = \Users::connect()
            ->where([
                'username' => $this->username
            ])
            ->find();
        if (!$user) {
            $password = Methods::encrypt($this->password, 'neotis');
            $user = \Users::connect()
                ->values([
                    'username' => $this->username,
                    'password' => $password,
                    'type' => $this->type
                ])
                ->add();
            if ($user) {
                $this->user['id'] = $user['id'];
            } else {
                $this->status = false;
                $this->errors[] = $this->messages->get('register_problem');
            }
        } else {
            $this->status = false;
            $this->errors[] = $this->messages->get('username_exist');
        }
    }

    /**
     * Complete user information
     * @param $form
     * @return bool
     */
    public function complete($form)
    {
        $form['relation'] = $this->user['id'];
        $info = \UsersInfo::connect()->values($form)->add();
        if (!$info) {
            $this->status = false;
            $this->errors[] = $this->messages->get('complete_info');
        }
    }
}
