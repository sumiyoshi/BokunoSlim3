<?php

namespace Core\Validator\Rule;

abstract class AbstractRule
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $element_name = null;

    /**
     * @var array
     */
    protected $configs;

    protected $default_configs = [];

    protected $require_config_parameters = [];

    /**
     * @param string $element_name
     * @param array $configs
     *
     * @throws \Exception
     */
    function __construct($element_name, $configs = [])
    {
        $this->element_name = $element_name;
        $this->setConfigs($configs);
    }

    /**
     * @param mixed $value
     * @param array $context
     *
     * @return bool
     */
    abstract public function validate($value, $context);

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        $message = $this->configs['message'];

        return $message;
    }


    protected function setConfigs($configs)
    {
        $this->configs = $configs + $this->default_configs;

        foreach ($this->require_config_parameters as $param_name) {

            if (!isset($this->configs[$param_name])) {
                throw new \Exception("{$this->name} rule expect '{$param_name}' option.");
            }
        }
    }

    /**
     * @param $group
     * @return bool
     */
    public function hasGroups($group)
    {
        if (isset($this->configs['groups'])) {
            return ($this->configs['groups'] == $group);
        } else {
            return true;
        }
    }

    public function getRuleName()
    {
        return $this->name;
    }

    public function isBreakOnFailure()
    {
        if (isset($this->configs['breakOnFailure']) && $this->configs['breakOnFailure'] === false) {
            return false;
        } else {
            return true;
        }
    }
}