<?php

namespace Core\Twig\Extension;

use Component\Service\Util\CommonUtil;
use Core\Util\DateUtil;

class Form extends \Twig_Extension
{
    protected $datas;

    protected $errors;

    protected $errors_list;

    protected $error_config;

    public function getName()
    {
        return 'form';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_default_value', array($this, 'default_value')),
            new \Twig_SimpleFunction('form_set_errors', array($this, 'set_errors')),
            new \Twig_SimpleFunction('form_error', array($this, 'error'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_hidden', array($this, 'hidden'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_all_hidden', array($this, 'all_hidden'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_text', array($this, 'text'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_number', array($this, 'number'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_tel', array($this, 'tel'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_email', array($this, 'email'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_password', array($this, 'password'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_checkbox', array($this, 'checkbox'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_select', array($this, 'select'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_textarea', array($this, 'textarea'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_file', array($this, 'file'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_date_format', array($this, 'date_format'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_date_format_time', array($this, 'date_format_time'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_access_token', array($this, 'access_token'), array('is_safe' => array('html')))
        );
    }


    /**
     * @param $key
     * @return null|string
     */
    public function error($key)
    {
        $html = null;

        if (isset($this->errors_list[$key])) {
            $error = $this->errors_list[$key];
            $html .= ' <div class="error_box">';
            $html .= ' <p>';
            $html .= '<img src="/img/warning.png" width="20" height="20" />';
            $html .= '<span>' . $error . '</span>';
            $html .= ' </p>';
            $html .= ' </div>';
        }
        return $html;
    }

    /**
     * @return string
     */
    public function access_token()
    {
        /** @var \Core\Service\CSRFToken $CSRFToken */
        $CSRFToken = \Core\Service\CSRFToken::getInstance();
        $html = '<input type="hidden" name="access_token" value="' . $CSRFToken->generate(60 * 20) . '" />';
        return $html;
    }

    /**
     * @param $time
     * @return bool|string
     */
    public function date_format_time($time)
    {

        if (!$time) {
            return '';
        }

        $explode_time = explode(':', $time);

        if (count($explode_time) != 2) {
            return false;
        }

        return $explode_time[0] . '時' . $explode_time[1] . '分';
    }

    /**
     * @param $date
     * @param $format
     * @param bool|false $addWeek
     * @return bool|string
     */
    public function date_format($date, $format, $addWeek = false)
    {

        if (!$date) {
            return '';
        }

        $w = DateUtil::getWeek($date);
        $date = date($format, strtotime($date));

        if ($addWeek) {
            $date .= '(' . $w . ')';
        }

        return $date;
    }

    /**
     * @param array $datas
     */
    public function default_value($datas)
    {
        $this->datas = $datas;
    }

    public function set_errors($errors)
    {
        $this->errors_list = $errors;
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function hidden($name, $attributes = array())
    {
        return $this->input('hidden', $name, $attributes);
    }

    public function all_hidden($non_hidden = array())
    {

        $html = null;
        foreach ($this->datas as $name => $key) {

            if ($name == 'access_token') {
                continue;
            }

            if ($name == 'admin_mode') {
                continue;
            }

            if (array_search($name, $non_hidden) === false) {
                $html .= $this->hidden($name);
            }
        }

        return $html;
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function text($name, $attributes = array())
    {
        return $this->input('text', $name, $attributes);
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function number($name, $attributes = array())
    {
        return $this->input('number', $name, $attributes);
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function tel($name, $attributes = array())
    {
        return $this->input('tel', $name, $attributes);
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function email($name, $attributes = array())
    {
        return $this->input('email', $name, $attributes);
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function password($name, $attributes = array())
    {
        return $this->input('password', $name, $attributes);
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function file($name, $attributes = array())
    {
        return $this->input('file', $name, $attributes);
    }

    /**
     * @param string $type
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    protected function input($type, $name, $attributes = array())
    {
        $attributes['name'] = $name;
        $attributes['type'] = $type;

        if (isset($this->datas[$name])) {
            $attributes['value'] = $this->datas[$name];
        }

        if (isset($this->errors[$name])) {
            if (!isset($attributes['class'])) {
                $attributes['class'] = '';
            }
            $attributes['class'] .= ' ' . $this->error_config['class'];
        }

        $attributes = $this->toStringAttributes($attributes);


        $html = "<input {$attributes}>";

        if (isset($this->errors[$name])) {
            $html .= "<span class='error'>";
            $html .= implode('<br>', $this->errors[$name]);
            $html .= "</span>";
        }

        return $html;
    }

    /**
     * チェックボックスが一個の時
     *
     * $values に [true=>1, false=>0] とやるとチェックしなくても必ず $name のパラメータがわたる
     * $values がスカラ値だったらチェックした時しか渡らない
     *
     * @param string $name
     * @param string|int|array $values
     * @param array $attributes
     *
     * @return string
     */
    public function checkbox($name, $values, $attributes = array())
    {
        $html = '';

        if (is_array($values)) {
            $true_value = $values['true'];
            $false_value = $values['false'];
            $html .= "<input name=\"$name\" type=\"hidden\" value=\"{$false_value}\">";
        } else {
            $true_value = $values;
        }

        $attributes['name'] = $name;
        $attributes['type'] = 'checkbox';
        $attributes['value'] = $true_value;

        if (isset($this->errors[$name])) {
            if (!isset($attributes['class'])) {
                $attributes['class'] = '';
            }
            $attributes['class'] .= ' ' . $this->error_config['class'];
        }

        $attributes = $this->toStringAttributes($attributes);


        if (isset($this->datas[$name]) && $this->datas[$name] == $true_value) {
            $ckecked = " checked";
        } else {
            $ckecked = "";
        }

        $html .= "<input {$attributes}{$ckecked}>";

        if (isset($this->errors[$name])) {
            $html .= "<span class='error'>";
            $html .= implode('<br>', $this->errors[$name]);
            $html .= "</span>";
        }

        return $html;
    }

    /**
     * @param string $name
     * @param array $options
     * @param array $attributes
     * @param string|null $empty
     *
     * @return string
     */
    public function select($name, $options, $attributes = array(), $empty = null, $alias = null)
    {
        $attributes['name'] = $name;

        if ($alias) {
            $selected_value = (isset($this->datas[$alias])) ? (string)$this->datas[$alias] : null;
        } else {
            $selected_value = (isset($this->datas[$name])) ? (string)$this->datas[$name] : null;
        }


        if (isset($this->errors[$name])) {
            if (!isset($attributes['class'])) {
                $attributes['class'] = '';
            }
            $attributes['class'] .= ' ' . $this->error_config['class'];
        }

        $attributes = $this->toStringAttributes($attributes);

        $html = "<select {$attributes}>";

        if (!is_array($options)) {
            $options = array();
        }

        if (!is_null($empty)) {
            $options = array('' => $empty) + $options;
        }

        foreach ($options as $value => $label) {
            $value = htmlspecialchars($value, ENT_QUOTES);
            $label = htmlspecialchars($label, ENT_QUOTES);
            $selected = ($selected_value === (string)$value) ? ' selected' : '';
            $html .= '<option' . $selected . ' value="' . $value . '">' . $label . '</option>';
        }

        $html .= '</select>';

        if (isset($this->errors[$name])) {
            $html .= "<span class='error'>";
            $html .= implode('<br>', $this->errors[$name]);
            $html .= "</span>";
        }

        return $html;
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    public function textarea($name, $attributes = array())
    {
        $attributes['name'] = $name;

        if (isset($this->errors[$name])) {
            if (!isset($attributes['class'])) {
                $attributes['class'] = '';
            }
            $attributes['class'] .= ' ' . $this->error_config['class'];
        }

        $attributes = $this->toStringAttributes($attributes);

        $html = "<textarea {$attributes}>";

        if (isset($this->datas[$name])) {
            $html .= htmlspecialchars($this->datas[$name], ENT_QUOTES);
        }

        $html .= '</textarea>';

        if (isset($this->errors[$name])) {
            $html .= "<span class='error'>";
            $html .= implode('<br>', $this->errors[$name]);
            $html .= "</span>";
        }

        return $html;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    protected function toStringAttributes($attributes)
    {
        $attrs = array();
        foreach ($attributes as $key => $value) {
            $value = htmlspecialchars($value, ENT_QUOTES);
            $attrs[] = "{$key}=\"{$value}\"";
        }

        return implode(' ', $attrs);
    }

}