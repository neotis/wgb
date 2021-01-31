<?php
/**
 * Base controller
 * Created by PhpStorm.

 * Date: 11/17/2017
 * Time: 1:17 PM
 * Neotis framework
 */

use Neotis\Core\Mvc\Controller;
use Neotis\Core\Template\Factory as Template;
use Neotis\Plugins\Seo\Tags;

class ControllerBase extends Controller
{
    /**
     * Store base url
     * @var string
     */
    protected $baseUrl = '';

    /**
     * ControllerBase constructor.
     */
    public function initial()
    {
        Template::$version = 1;

        Template::setBaseTitle('Neotis Framework');
        Template::addTopJs("/files/packages/base/js/jquery.min.js");
        Template::addCss("/files/packages/base/fonts/styles.css");

        Tags::$baseUrl = $this->baseUrl;
        $tagsManager = new Tags();
        $tagsManager
            ->title('Neotis Framework')
            ->description('PHP Framework')
            ->keys('PHP, Framework, Programming, Website');
    }
}
