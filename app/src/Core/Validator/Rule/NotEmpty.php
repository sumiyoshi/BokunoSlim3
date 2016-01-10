<?php

namespace Core\Validator\Rule;

/**
 * Class NotEmpty
 *
 * @package Fat\Validator
 */
class NotEmpty extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "NotEmpty";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => '必須項目です',
        'breakOnFailure' => true,
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
     * @throws \Exception ,
     * @return bool
     */
    public function validate($value, $context)
    {
        if (!is_scalar($value) && !is_null($value)) {
            throw new \Exception('invalid value in NotEmpty.');
        }

        $validate = true;

        $str_value = (string)$value;

        if ($str_value === '') {
            $validate = false;
        }

        return $validate;
    }
}