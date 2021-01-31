<?php

/**
 * Check status of login
 * Created by PhpStorm.
 * Date: 1/6/2019
 * Time: 9:34 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Users;

use Neotis\Core\Router\Router;
use Neotis\Plugins\Cookie\Adapter as Cookie;
use Neotis\Core\Session\Adapter as Session;
use Neotis\Plugins\Plugins;


class Status extends Plugins
{
    /**
     * Check login status of user
     */
    public function check()
    {
        $user = Session::get('user');
        if ($user) {//If user session is exist
            Router::setUserId($user['id']);
            Router::setUserType($user['type']);
        } else {//If user cookie is exist
            $cookie = Cookie::get('user');
            if (isset($cookie['id']) and isset($cookie['type'])) {
                Router::setUserId($cookie['id']);
                Router::setUserType($cookie['type']);
            }
        }
    }
}
