<?php

namespace Core\Validator\Rule;

/**
 * Class Number
 *
 * @package Fat\Validator
 */
class Number extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "Number";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => '半角数字で入力してください',
        'groups' => 'default'
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'groups'];

    /**
     * @param mixed $value
     * @param array $context
     *
     * @return bool
     */
    public function validate($value, $context)
    {
        $validate = true;

        if (!preg_match('/^\d*$/', $value)) {
            $validate = false;
        }

        return $validate;
    }
}