<?php

namespace Core\Validator\Rule;


/**
 * Class Regex
 *
 * @package Core\Validator\Rule
 */
class Regex extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "Regex";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => '形式が正しくありません',
        'groups' => 'default'
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'groups', 'pattern'];

    /**
     * @param mixed $value
     * @param array $context
     *
     * @return bool
     */
    public function validate($value, $context)
    {
        $validate = true;

        $status = preg_match($this->configs['pattern'], $value);

        if ($status === 0) {
            $validate = false;
        }

        return $validate;
    }
}