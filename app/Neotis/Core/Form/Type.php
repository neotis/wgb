<?php
/**
 * Define type of element
 * Created by PhpStorm.

 * Date: 2/1/2019
 * Time: 12:51 AM
 * Neotis framework
 */

namespace Neotis\Core\Form;

use Neotis\Cli\Neotis;
use Neotis\Core\Form\Options\Password;
use Neotis\Core\Form\Options\Text;
use Neotis\Core\Form\Adapter as Form;

class Type extends Neotis
{
    /**
     * Create text element
     */
    public function text()
    {
        Form::$form[Form::$name]['type'] = 'text';
        return (new Text());
    }

    public function password()
    {
        Form::$form[Form::$name]['type'] = 'password';
        return (new Password());
    }

    public function autoComplete()
    {

    }

    public function image()
    {

    }

    public function file()
    {

    }

    public function switcher()
    {

    }

    public function radio()
    {

    }

    public function checkBox()
    {

    }

    public function textArea()
    {

    }

    public function ckEditor()
    {

    }

    public function tinyMce()
    {

    }

    public function selectBox()
    {

    }

    public function multiple()
    {

    }

    public function tags()
    {

    }

    public function date()
    {

    }

    public function rangeSlider()
    {

    }

    public function proImage()
    {

    }

    public function category()
    {

    }

    public function hidden()
    {

    }

    public function map()
    {

    }
}
