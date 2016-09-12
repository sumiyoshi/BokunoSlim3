<?php

namespace Core\Validator;

use Core\Validator\Rule\AbstractRule;
use ReflectionClass;

/**
 * Class Validator
 *
 * @package Fat\Validator
 *
 * @method Validator NotEmpty($element_name, $configs = [])
 * @method Validator InArray($element_name, $configs = [])
 * @method Validator Length($element_name, $configs = [])
 * @method Validator Callback($element_name, $configs = [])
 * @method Validator Number($element_name, $configs = [])
 * @method Validator Mailaddress($element_name, $configs = [])
 * @method Validator DateTime($element_name, $configs = [])
 * @method Validator Regex($element_name, $configs = [])
 * @method Validator Date($element_name, $configs = [])
 *
 */
class Validator implements ValidatorInterface
{
    /**
     * @var AbstractRule[][]
     */
    private $rules = [];

    /**
     * @var null|array
     */
    private $messages = null;

    /**
     * @var array
     */
    private $groups = [
        'default'
    ];

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return Validator
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {

        $klass = '\\Core\\Validator\\Rule\\' . $name;

        if (!class_exists($klass)) {
            throw new \Exception('Call to undefined function. ' . $name . '()');
        }

        $element_name = $arguments[0];
        $configs = (isset($arguments[1])) ? $arguments[1] : [];

        if (is_null($element_name)) {
            throw new \Exception('element name is required');
        }

        $class = new ReflectionClass($klass);
        $rule = $class->newInstanceArgs([$element_name, $configs]);

        if (!isset($this->rules[$element_name])) {
            $this->rules[$element_name] = [];
        }

        $this->rules[$element_name][] = $rule;

        return $this;
    }

    /**
     * @param $data
     * @return bool
     */
    public function validate($data)
    {
        $valid = true;

        $this->messages = [];

        foreach ($this->rules as $element_name => $element_rules) {

            /**
             * @var AbstractRule[] $element_rules
             */
            $value = (isset($data[$element_name])) ? $data[$element_name] : null;

            foreach ($element_rules as $element_rule) {

                $rule_name = $element_rule->getRuleName();

                if ($element_rule->hasGroups($this->groups) && $element_rule->validate($value, $data) === false) {
                    $valid = false;
                    if (!isset($this->messages[$element_name])) {
                        $this->messages[$element_name] = [];
                    }
                    if (!isset($this->messages[$element_name][$rule_name])) {
                        $this->messages[$element_name][$rule_name] = [];
                    }
                    $this->messages[$element_name][$rule_name][] = $element_rule->getErrorMessage();

                    if ($element_rule->isBreakOnFailure()) {
                        break;
                    }
                }
            }
        }

        return $valid;
    }

    /**
     * @param $group
     * @return $this
     */
    public function addGroups($group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * @param $group
     * @return $this
     */
    public function setGroups($group)
    {

        if (is_array($group)) {
            $this->groups = $group;
        } else {
            $this->groups = [$group];
        }

        return $this;
    }

    /**
     * @param $group
     * @return $this
     */
    public function resetGroups($group)
    {
        if (isset($this->groups[$group])) {
            unset($this->groups[$group]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}