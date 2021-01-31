<?php
/**
 * Register user in api
 * Created by PhpStorm.

 * Date: 7/3/2018
 * Time: 12:47 PM
 * Neotis framework
 */

namespace Neotis\Api\Users;

use Neotis\Core\Adapter\Ini;
use Neotis\Core\Security\Authentication as Auth;
use Neotis\Core\Services\Message;
use Neotis\Core\Services\Validation;

class Register extends Users
{
    /**
     * Register user to application
     * @param $type
     * @param $username
     * @param $email
     * @param $mobile
     * @param $password
     * @return mixed
     */
    public function _do($type = '2', $password = false, $username = false, $email = false, $mobile = false)
    {
        $this->message = new Message();
        $language = APP_PATH . 'app/config/languages/api.ini';
        $ini = new Ini($language);
        $this->language = $ini->settings;
        $info = $this->prepareInfo($password, $username, $email, $mobile);
        if(!empty($info['username'])){
            $this->usernameExist($info['username']);
        }
        if(!empty($info['email'])){
            $this->emailExist($info['email']);
        }
        if(!empty($info['mobile'])){
            $this->mobileExist($info['mobile']);
        }
        $info['type'] = $type;
        $user = \Users::add($info);
        return $user;
    }

    /**
     * If username is not exist on database
     * @param $username
     * @return bool
     */
    private function usernameExist($username)
    {
        $user = \Users::find([
            'where' => [
                'username' => $username
            ]
        ]);
        if(empty($user)){
            return true;
        }else{
            $this->message->json('0', 'false', $this->language->database->username);
        }
    }

    /**
     * If email is not exist on database
     * @param $email
     * @return bool
     */
    private function emailExist($email)
    {
        $user = \Users::find([
            'where' => [
                'email' => $email
            ]
        ]);
        if(empty($user)){
            return true;
        }else{
            $this->message->json('0', 'false', $this->language->datbase->email);
        }
    }

    /**
     * If mobile is not exist on database
     * @param $mobile
     * @return bool
     */
    private function mobileExist($mobile)
    {
        $user = \Users::find([
            'where' => [
                'mobile' => $mobile
            ]
        ]);
        if(empty($user)){
            return true;
        }else{
            $this->message->json('0', 'false', $this->language->datbase->mobile);
        }
    }

    /**
     * Prepare information to register user
     * @param bool $password
     * @param bool $username
     * @param bool $email
     * @param bool $mobile
     * @return array
     */
    private function prepareInfo($password = false, $username = false, $email = false, $mobile = false)
    {
        //If email is not valid
        if($email){
            if(!Validation::email($email)){
                $this->message->json('0', 'false', $this->language->regex->email_regex);
            }
        }

        //If mobile is not valid
        if($mobile){
            if(!Validation::mobile($mobile)){
                $this->message->json('0', 'false', $this->language->regex->mobile_regex);
            }
        }

        //If email is not valid
        if($password){
            if(!Validation::password($password)){
                $this->message->json('0', 'false', $this->language->regex->password_regex);
            }
        }

        //If username is not valid
        if($username){
            if(!Validation::username($username)){
                $this->message->json('0', 'false', $this->language->regex->username_regex);
            }
        }

        //If password is not defined
        if(!$password){
            $this->message->json('0', 'false', $this->language->regex->password_require);
        }

        //If username,email and mobile is not defined
        if(!$username and !$email and !$mobile){
            $this->message->json('0', 'false', $this->language->regex->email_mobile_username_require);
        }

        $password = Auth::encrypt($password);

        if($username and $mobile and $email){
            $info = [
                'username' => $username,
                'mobile' => $mobile,
                'email' => $email,
                'password' => $password
            ];
        }elseif(!$username and $mobile and $email){
            $info = [
                'username' => $email,
                'mobile' => $mobile,
                'email' => $email,
                'password' => $password
            ];
        }elseif(!$username and !$mobile and $email){
            $info = [
                'username' => $email,
                'mobile' => '',
                'email' => $email,
                'password' => $password
            ];
        }elseif(!$username and $mobile and !$email){
            $info = [
                'username' => $mobile,
                'mobile' => $mobile,
                'email' => '',
                'password' => $password
            ];
        }

        return $info;
    }
}
