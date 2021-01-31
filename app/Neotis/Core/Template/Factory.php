<?php
/**
 * Template factory
 * Created by PhpStorm.

 * Date: 10/17/2018
 * Time: 3:35 PM
 * Neotis framework
 */

namespace Neotis\Core\Template;

use Neotis\Core\Http\Header;
use Neotis\Core\Ini\Display as BaseConfig;
use Neotis\Core\Neotis;
use Neotis\Core\Router\Router;
use Neotis\Core\Template\Html\Manipulator as Html;
use Neotis\Core\Template\Css\Manipulator as Css;
use Neotis\Core\Template\MetaTags\Manipulator as MetaTags;
use Neotis\Core\Template\Js\Manipulator as Script;
use Neotis\Core\Template\Result\Title;
use Neotis\Core\Watcher\Manage as Watcher;


class Factory extends Neotis
{
    /**
     * Result manager
     */
    use Html;

    /**
     * Css manager
     */
    use Css {
        run as runCss;
        add as addCss;
    }

    /**
     * Meta tags manager
     */
    use MetaTags;

    /**
     * Script manager
     */
    use Script;

    /**
     * Title manager
     */
    use Title;


    /**
     * Store name of current package
     * @var string
     */
    private $package = '';

    /**
     * Define name of component tag for partial render
     * @var string
     */
    public static $componentTag = '';

    /**
     * Store variable for transfer to view manager
     * @var null
     */
    public static $variables = [];

    /**
     * Define verison of styles
     * @var int
     */
    public static $version = 0;


    /**
     * Define variables for transfer to view
     * @param array $vars
     */
    public static function vars(array $vars)
    {
        foreach ($vars as $key => $value) {
            self::$variables[$key] = $value;
        }
    }

    /**
     * Run template factory
     * @throws \Neotis\Core\Exception\Exception
     */
    public function run()
    {
        $this->package = Router::getPackage();
        $frameworkConfig = BaseConfig::$settings;
        if (((((strtolower($frameworkConfig['default']['developer']) == true and Router::getUserId() == '2') or strtolower($frameworkConfig['default']['developer']) == 2) and !Header::getJson()) or Watcher::$watcher)) {
            if (!Header::$component) {
                $this->runCss();
                $this->runScript();
                $this->prepareResult();
            }elseif(Header::$component and !empty(self::$componentTag)){
                $this->runCss();
                $this->runScript();
                $this->prepareResult();
            }
        }
    }
}
