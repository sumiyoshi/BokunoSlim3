<?php

namespace Core\Validator\Rule;

/**
 * Class DateTime
 *
 * @package Fat\Validator
 */
class DateTime extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "DateTime";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => '正しくない日付です',
        'groups' => 'default'
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'groups'];

    /**
     * @param string $value
     * @param array $context
     *
     * @throws \Exception
     * @return bool
     */
    public function validate($value, $context)
    {
        if (!preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2}) ([0-9]{2})\:([0-9]{2})\:([0-9]{2})$/', $value, $match)) {
            return false;
        }
        
        if (!checkdate($match[2], $match[3], $match[1])) {
            return false;
        }
        
        return true;
    }
}
