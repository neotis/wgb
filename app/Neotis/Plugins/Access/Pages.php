<?php
/**
 * Define access to pages with user login information
 * Created by PhpStorm.

 * Date: 12/27/2018
 * Time: 4:30 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Access;

use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Core\Router\Router;
use Neotis\Plugins\Plugins;

class Pages extends Plugins
{
    /**
     * Fetch current access information in database
     */
    private function currentAccess()
    {
        $package = Router::getPackage();
        $controller = Router::getController();
        $action = Router::getAction();
        $json = Header::getJson();
        $method = Request::getMethod();
        $display = 'html';
        if ($json) {
            $display = 'json';
        }
        $where = [
            'package' => strtolower($package),
            'controller' => strtolower($controller),
            'action' => strtolower($action),
            'method' => strtolower($method),
            'display' => $display,
            'deleted' => 0
        ];

        $page = \AccessesPages::connect()
            ->where($where)->cache(180)
            ->find();
        if (!$page or $page['status'] == '1') {
            return false;
        } else {
            return $page;
        }
    }

    /**
     * Fetch user type access status
     * @param $id
     * @return bool | int
     */
    private function userTypeAccess($id)
    {
        $userType = Router::getUserType();

        $userType = \AccessesGroups::connect()
            ->where([
                '_group' => $userType,
                'page' => $id
            ])->cache(180)
            ->find();

        return $userType;
    }

    /**
     * Fetch user access status
     * @param $id
     * @return bool
     */
    private function userAccess($id)
    {
        $userId = Router::getUserId();
        $userAccess = \AccessesUsers::connect()
            ->where([
                '_user' => $userId,
                'page' => $id
            ])->cache(180)
            ->find();
        return $userAccess;
    }

    /**
     * Determine access status of selected package pages
     * if it's return "true" everybody can access to current page
     * this value can be set on config file in package folder
     * default value is "false"
     * recommended value for deploy status is "false"
     */
    private function packagesAccessStatus()
    {
        $config = Router::getPackagesConfigs();
        $package = Router::getPackage();
        if (isset($config['packages'][$package]['security_access'])) {
            return $config['packages'][$package]['security_access'];
        } else {
            return false;
        }
    }

    /**
     * Run access plugin for check user status
     */
    public function run()
    {
        $pageStatus = $this->currentAccess();
        $result = true;
        $redirect = 'http/_403';
        $action = Router::getAction();
        if($action !== '_401' and $action !== '_403' and $action !== '_404' and $action !== '_500'
            and $action !== '_502' and $action !== '_503'){

            if (!$pageStatus) {
                $result = false;
            } else {
                $user = $this->userAccess($pageStatus['id']);
                $userType = $this->userTypeAccess($pageStatus['id']);
                $redirect = $pageStatus['redirect'];

                if (isset($user['redirect']) and !empty($user['redirect'])) {
                    $redirect = $user['redirect'];
                } elseif (isset($userType['redirect']) and !empty($userType['redirect'])) {
                    $redirect = $userType['redirect'];
                } elseif (isset($pageStatus['redirect']) and !empty($pageStatus['redirect'])) {
                    $redirect = $pageStatus['redirect'];
                }

                if (!$user) {
                    if (!$userType) {
                        $result = false;
                    } elseif ($userType['status'] == '1') {
                        if (!empty($userType['redirect'])) {
                            $result = false;
                        } else {
                            if (!empty($pageStatus['redirect'])) {
                                $result = false;
                            } else {
                                $result = false;
                            }

                        }
                    }
                } elseif ($user['status'] == '1') {
                    if (!empty($user['redirect'])) {
                        $result = false;
                    } else {
                        if (!empty($pageStatus['redirect'])) {
                            $result = false;
                        } else {
                            $result = false;
                        }
                    }
                }
            }
        }else{
            $result = true;
        }

        if ($this->packagesAccessStatus()) {
            $result = true;
        }
        if (!$result) {
            Header::add('Redirect: ' . $redirect);
            Router::forwarder($redirect, 403);
        }
    }
}
