<?php
/**
 * Neotis jalali date plugin
 * Created by PhpStorm.
 * User: developer
 * Date: 7/12/2017
 * Time: 2:26 PM
 * Neotis framework
 * @router class
 * @Run application
 */

namespace Neotis\Plugins\Validation;

use Neotis\Core\Languages\Basic as Language;
use Neotis\Core\Http\Request;
use Neotis\Plugins\Plugins;

class Form extends Plugins
{
    /**
     * Store http method for fetch information
     * @var string
     */
    public $method = 'POST';

    /**
     * Store validation errors
     * @var array
     */
    public $errors = [];

    /**
     * Store response of form validation
     * @var array
     */
    public $response = [];

    /**
     * Status of validation form
     * @var bool
     */
    public $status = true;

    /**
     * Store messages
     * @var array
     */
    private $messages = [];


    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->messages = new Language('Plugins' . DS . 'Validation' . DS . 'Validator');
    }

    /**
     * Prepare form value by http method
     * @param $index
     * @return array|bool|mixed|string
     */
    private function valueCalculator($index)
    {
        $value = '';
        if (strtolower($this->method) === 'post') {
            $value = Request::getPost($index);
        } elseif (strtolower($this->method) === 'get') {
            $value = Request::getQuery($index);
        } elseif (strtolower($this->method) === 'put') $value = Request::getPut($index);

        if ($value === false) {
            $value = '';
        }

        return $value;
    }

    /**
     * Check the input to make it require or not
     * @param $validator
     * @param $name
     * @param $value
     * @param $require
     * @return string
     */
    private function requireCalculator($validator, $name, $value, $require)
    {
        if ($require and empty($value)) {
            $this->status = false;
            $this->errors[$name] = str_ireplace('name', $name, $this->messages->get($validator . '_require'));
            return '';
        }
        return $value;
    }


    /**
     * validate form value and user input
     * Calculate response and errors message based on selected validation
     * @param $validator
     * @param $name
     * @param $index
     * @param bool $require
     * @return Form
     */
    public function validate($validator, $name, $index, $require = false)
    {
        $value = $this->valueCalculator($index);
        $value = $this->requireCalculator($validator, $name, $value, $require);
        if ($validator != 'none') {
            $validate = Validator::{$validator}($value);
        }else{
            $validate = true;
        }

        if ((!$validate and $require) or (!$validate and !empty($value))) {
            $this->status = false;
            $this->errors[$name] = str_ireplace('name', $name, $this->messages->get($validator . '_validate'));
            return $this;
        }
        if (!empty($value)) {
            $this->response[$name] = $value;
        }
        return $this;
    }
}
