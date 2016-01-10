<?php

namespace Core\Validator\Rule;

/**
 * Class Length
 *
 * @package Fat\Validator
 */
class Length extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "Length";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => [
            '{min}文字以上で入力してください',
            '{max}文字以下で入力してください',
            '{min}文字以上{max}文字以下で入力してください'
        ],
        'charset' => 'UTF-8',
        'groups' => 'default'
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'groups'];

    protected function setConfigs($configs)
    {
        parent::setConfigs($configs);

        if (!isset($this->configs['min']) && !isset($this->configs['max'])) {
            throw new \Exception("{$this->name} rule expect 'min' or 'max' option.");
        }
    }


    /**
     * @param mixed $value
     * @param array $context
     *
     * @throws \Exception
     * @return bool
     */
    public function validate($value, $context)
    {
        if (!is_scalar($value) && !is_null($value)) {
            throw new \Exception('invalid value in Length');
        }

        $validate = true;

        $min     = (isset($this->configs['min'])) ? $this->configs['min'] : null;
        $max     = (isset($this->configs['max'])) ? $this->configs['max'] : null;
        $charset = $this->configs['charset'];

        $len = mb_strlen($value, $charset);

        if ($len == 0) {
            return true;
        }

        if (!is_null($min) && $min > $len) {
            $validate = false;
        }

        if (!is_null($max) && $max < $len) {
            $validate = false;
        }

        return $validate;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        if (is_string($this->configs['message'])) {
            return $this->configs['message'];
        }

        $message = '';

        if (isset($this->configs['min']) && isset($this->configs['max'])) {
            $message = $this->configs['message'][2];
            $message = str_replace('{min}', $this->configs['min'], $message);
            $message = str_replace('{max}', $this->configs['max'], $message);
        } else if (isset($this->configs['min'])) {
            $message = $this->configs['message'][0];
            $message = str_replace('{min}', $this->configs['min'], $message);
        } else if (isset($this->configs['max'])) {
            $message = $this->configs['message'][1];
            $message = str_replace('{max}', $this->configs['max'], $message);
        }

        return $message;
    }
}