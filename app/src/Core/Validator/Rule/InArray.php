<?php

namespace Core\Validator\Rule;

/**
 * Class InArray
 *
 * @package Fat\Validator
 */
class InArray extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "InArray";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => '正しくない値です',
        'groups' => 'default'
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'haystack', 'groups'];

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
            throw new \Exception('invalid value in InArray.');
        }

        $valid = true;

        if (!in_array($value, $this->configs['haystack'])) {
            $valid = false;
        }

        return $valid;
    }
}