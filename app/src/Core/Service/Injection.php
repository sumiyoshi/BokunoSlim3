<?php

namespace Core\Service;

/**
 * Class Injection
 *
 * @package Core\Service
 * @author sumiyoshi
 */
class Injection
{

    /**
     * @var \ReflectionClass
     */
    private $reader;

    /**
     * @var array
     */
    private $injectionClass = [];

    private $injectionName = [];

    public function __construct($name)
    {
        $this->reader = new \ReflectionClass($name);
    }

    /**
     * @param array $injectionClass
     * @return $this
     */
    public function setInjectionClass(array $injectionClass)
    {
        $this->injectionClass = $injectionClass;

        return $this;
    }

    /**
     * @param array $injectionName
     * @return $this
     */
    public function setInjectionName(array $injectionName)
    {
        $this->injectionName = $injectionName;

        return $this;
    }

    /**
     * @param $name
     * @param $object
     * @return $this
     */
    public function addInjectionClass($name, $object)
    {
        $this->injectionClass[$name] = $object;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addInjectionName($name, $value)
    {
        $this->injectionClass[$name] = $value;

        return $this;
    }

    /**
     * @return object
     */
    public function newInstance()
    {
        $parameters = $this->reader->getMethod('__construct')->getParameters();

        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = $this->getArgument($parameter);
        }

        return $this->reader->newInstanceArgs($args);
    }

    /**
     * @param $name
     * @param $object
     * @return mixed
     */
    public function invoke($name, $object)
    {
        $parameters = $this->reader->getMethod($name)->getParameters();

        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = $this->getArgument($parameter);
        }

        return $this->reader->getMethod($name)->invokeArgs($object, $args);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return null
     */
    private function getArgument(\ReflectionParameter $parameter)
    {
        if ($class = $parameter->getClass()) {
            $className = $class->getName();

            if (isset($this->injectionClass[$className])) {
                return $this->injectionClass[$className];
            } else {
                $reader = new \ReflectionClass($className);
                $parameters = $reader->getMethod('__construct')->getParameters();

                $args = [];
                foreach ($parameters as $parameter) {
                    $args[] = $this->getArgument($parameter);
                }
                return $reader->newInstanceArgs($args);
            }
        } elseif ($valueName = $parameter->getName()) {

            if (isset($this->injectionName[$valueName])) {
                return $this->injectionName[$valueName];
            } else {
                return null;
            }
        }

        return null;
    }
}