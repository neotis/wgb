<?php
/**
 * Manipulate documentation
 * Created by PhpStorm.

 * Date: 12/27/2018
 * Time: 4:30 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Docs;

use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Core\Router\Router;
use Neotis\Plugins\Plugins;

class Manipulator extends Plugins
{
    /**
     * Store result of manipulator
     * @var array
     */
    private $result = [];

    /**
     * Generate title
     * @param $titles
     * @param $current
     */
    private function titlesGenerator($titles, $current)
    {
        foreach ($titles as $key => $value) {
            if ($current === $value['selector']) {
                $this->result[$value['title']]['active'] = true;
            } else {
                if(!$this->result[$value['title']]['active']){
                    $this->result[$value['title']]['active'] = false;
                }
            }
            $this->result[$value['title']]['subtitles'][] = [
                'title' => $value['subtitle'],
                'selector' => $value['selector']
            ];
        }
    }

    /**
     * Run documentation manipulator
     * @param $titles
     * @param string $current
     * @return array
     */
    public function run($titles, $current = '')
    {
        $this->titlesGenerator($titles, $current);
        return $this->result;
    }
}
