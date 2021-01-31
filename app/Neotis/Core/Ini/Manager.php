<?php
/**
 * Ini manager for events
 * Created by PhpStorm.

 * Date: 10/4/2018
 * Time: 7:45 PM
 * Neotis framework
 */

namespace Neotis\Core\Ini;

use Neotis\Core\Ini\Display as Ini;

class Manager
{
    /**
     * Run to define basic settings of application
     * @throws \Matomo\Ini\IniReadingException
     */
    public function run()
    {
        /** Config ini */
        if (file_exists(APP_PATH . 'app/config/config.ini')) {
            $file = APP_PATH . 'app/config/config.ini';
            $settings = (new Ini())->run($file);
            $settings = json_decode(json_encode($settings), true);

            //Permit for all domains
            if (!isset($settings['url']['path']) and isset($_SERVER['SERVER_NAME'])) {
                $settings['url']['path'] = 'http://' . $_SERVER['SERVER_NAME'] . '/';
            }
            Ini::$settings = $settings;
        }
    }
}
