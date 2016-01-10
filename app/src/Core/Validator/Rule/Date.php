<?php

namespace Core\Validator\Rule;

/**
 * Class Callback
 *
 * @package Fat\Validator
 */
class Date extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "Date";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => '形式が不正です',
        'format' => false,
        'groups' => 'default'
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'format', 'groups'];

    private function checkDatetimeFormat($datetime)
    {
        return preg_match('/^(?P<year>[0-9]{4})\-(?P<month>[0-9]{2})-(?P<day>[0-9]{2})$/', $datetime, $m) === 1
        && checkdate($m['month'], $m['day'], $m['year']);
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

        if ($this->configs['format']) {
            $value = date($this->configs['format'], strtotime($value));
        }

        return $this->checkDatetimeFormat($value);
    }
}