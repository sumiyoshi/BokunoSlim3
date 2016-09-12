<?php

namespace Core\Validator\Rule;

/**
 * Class Callback
 *
 * @package Fat\Validator
 */
class Callback extends AbstractRule
{
    /**
     * @var string
     */
    protected $name = "Callback";

    /**
     * @var array
     */
    protected $default_configs = [
        'message' => 'invalid',
        'groups' => []
    ];

    /**
     * @var array
     */
    protected $require_config_parameters = ['message', 'callback', 'groups'];

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
            throw new \Exception('invalid value in Callback');
        }

        $callable = $this->configs['callback'];

        $validate = (bool)$callable($value, $context);

        return $validate;
    }
}