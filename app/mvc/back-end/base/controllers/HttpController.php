<?php
/**
 * Custom actions and pages for custom errors
 * Created by PhpStorm.

 * Date: 11/17/2017
 * Time: 1:17 PM
 * Neotis framework
 */

use Neotis\Core\Mvc\View;
use Neotis\Core\Template\Factory as Template;

class HttpController extends ControllerBase
{
    public function _401Action()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function _403Action()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function _404Action()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function _500Action()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function _502Action()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function _503Action()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }
    public function _401ActionJson()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function _403ActionJson()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function _404ActionJson()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function _500ActionJson()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function _502ActionJson()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function _503ActionJson()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }

    public function post_401()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function post_403()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function post_404()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function post_500()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function post_502()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function post_503()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }

    public function post_401Json()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function post_403Json()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function post_404Json()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function post_500Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function post_502Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function post_503Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }

    public function put_401()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function put_403()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function put_404()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function put_500()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function put_502()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function put_503()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }

    public function put_401Json()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function put_403Json()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function put_404Json()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function put_500Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function put_502Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function put_503Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }

    public function delete_401()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function delete_403()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function delete_404()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function delete_500()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function delete_502()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function delete_503()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }

    public function delete_401Json()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function delete_403Json()
    {
        View::setMainIndex('http');
        Template::setTitle('You don\'t have access to this page!');
    }

    public function delete_404Json()
    {
        View::setMainIndex('http');
        Template::setTitle('The requested page was not found!');
    }

    public function delete_500Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Internal Server Error');
    }

    public function delete_502Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Bad Gateway');
    }

    public function delete_503Json()
    {
        View::setMainIndex('http');
        Template::setTitle('Service Unavailable');
    }
}
