<?php
/**
 * Created by PhpStorm.

 * Date: 10/5/2018
 * Time: 8:11 PM
 * Neotis framework
 */

namespace Neotis\Core\Http;


use Neotis\Core\Neotis;

class Manager extends Neotis
{
    /**
     * Define basic headers
     */
    public function header()
    {
        Header::setRequests();
        Header::setHeaders();
        Header::json();
        Header::partial();
        Header::pureHtml();
        Header::authorization();
    }
}
