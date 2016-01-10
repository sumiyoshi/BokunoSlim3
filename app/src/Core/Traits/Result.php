<?php

namespace Core\Traits;

/**
 * Class Result
 * @package Core\Traits
 */
trait Result
{

    /** @var array */
    private $results = array();

    /** @var array */
    private $errors = array();

    /**
     * @param string $name
     * @param mixed $value
     */
    protected function addResult($name, $value)
    {
        $this->results[$name] = $value;
    }

    /**
     * @param $value
     */
    protected function setResult($value)
    {
        $this->results = $value;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param string $index
     * @param string $msg
     */
    protected function addError($index, $msg)
    {
        if (!isset($this->errors[$index])) {
            $this->errors[$index] = null;
        }

        $this->errors[$index] = $msg;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return (bool)$this->errors;
    }

    /**
     * @param array $errors
     */
    protected function setError(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
} 