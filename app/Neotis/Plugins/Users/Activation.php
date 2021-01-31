<?php
/**
 * Generate activation code for user
 * Created by PhpStorm.

 * Date: 12/27/2018
 * Time: 4:26 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Users;

use Neotis\Api\Users\Users;
use Neotis\Core\Cookie\Adapter as Cookie;
use Neotis\Core\Languages\Basic as Language;
use Neotis\Core\Services\Methods;
use Neotis\Plugins\Cookie\Adapter;
use Neotis\Plugins\Plugins;
use Neotis\Core\Exception\Exception;
use Neotis\Core\Session\Adapter as Session;
use Neotis\Plugins\Validation\Form;
use Neotis\Plugins\Validation\Validator;

class Activation extends Plugins
{
    /**
     * Store user id
     * @var integer
     */
    public $user;

    /**
     * Store user type to change user status
     * @var integer
     */
    public $type;

    /**
     * Store mobile number for send activation code
     * @var string
     */
    public $mobile = '';

    /**
     * Store resend time to resend new activation code
     * @var int
     */
    public $resendTime = 60;

    /**
     * Store activation code
     * @var string
     */
    public $code = '';

    /**
     * Define maximum time of try activation code
     * @var int
     */
    public $max = 3;

    /**
     * Store errors of user activation
     * @var array
     */
    public $errors = [];

    /**
     * Store messages class
     * @var array
     */
    private $messages = [];

    /**
     * Define type of user for request
     * @var bool
     */
    public $isAdmin = false;

    /**
     * Activation constructor.
     */
    public function __construct()
    {
        $this->messages = new Language('Plugins' . DS . 'Users' . DS . 'Activation');
    }

    /**
     * Generate new activation code
     */
    public function generate()
    {
        $this->code = Methods::random(4, 'numbers');

        //Check that there is a user ID in the database
        $user = \Users::connect()->where((int)$this->user)->find();

        if (!$user) {
            $this->errors[] = $this->messages->get('username_exist');
            return false;
        }

        //Check user information
        $userInfo = \UsersInfo::connect()->where([
            'relation' => $this->user
        ])->find();
        if (!$userInfo) {
            $this->errors[] = $this->messages->get('userinfo_exist');
            return false;
        }
        if(Validator::mobile($user['username'])){
            $this->mobile = $user['username'];
        }else{
            $this->mobile = $userInfo['mobile'];
        }

        //Check time limitation for resend activation code
        $lastCode = \UsersActivationCdes::connect()->where([
            'selected_user' => $this->user
        ])->find();
        if (isset($lastCode['timestamp']) and (time() - $lastCode['timestamp']) <= $this->resendTime) {
            $this->errors[] = str_ireplace((time() - $lastCode['timestamp']), '', $this->messages->get('complete_info'));
            return false;
        }

        \UsersActivationCdes::connect()->where([
            'selected_user' => $this->user
        ])->softDelete();

        \UsersActivationCdes::connect()->values([
            'code' => $this->code,
            'selected_user' => $this->user,
            'max' => $this->max
        ])->add();
        $this->errors[] = $this->messages->get('success_send');
        return true;
    }

    public function check()
    {
        //Check that there is a user ID in the database
        $user = \Users::connect()->where((int)$this->user)->find();
        if (!$user) {
            $this->errors[] = $this->messages->get('username_exist');
            return false;
        }

        //Check user information
        $userInfo = \UsersInfo::connect()->where([
            'relation' => $this->user
        ])->find();
        if (!$userInfo) {
            $this->errors[] = $this->messages->get('userinfo_exist');
            return false;
        }
        $this->mobile = $userInfo['mobile'];

        $activation = \UsersActivationCdes::connect()->where([
            'selected_user' => $this->user
        ])->find();

        if (!$activation) {
            $this->errors[] = $this->messages->get('activation_info');
            return false;
        }

        if($activation['count'] >= $activation['max']){
            $this->errors[] = $this->messages->get('max_use');
            return false;
        }

        if ($activation['code'] === $this->code) {
            \Users::connect()->where((int)$this->user)->values([
                'type' => $this->type
            ])->update();
            if(!$this->isAdmin){
                $user = Session::get('user');
                $user['type'] = $this->type;
                Session::set('user', $user);
            }
            return true;
        } else {
            $newCount = $activation['count'] + 1;
            \UsersActivationCdes::connect()->where([
                'selected_user' => $this->user
            ])->values(['count'=> $newCount])->update();
            $this->errors[] = $this->messages->get('wrong_code');
            return false;
        }
    }
}
