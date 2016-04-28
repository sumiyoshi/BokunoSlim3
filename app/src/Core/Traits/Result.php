<?php

namespace Core\Traits;

/**
 * Class Result
 *
 * @package Core\Traits
 * @author sumiyoshi
 */
trait Result
{

    /** @var array */
    private $results = array();

    /** @var array */
    private $errors = array();

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    protected function addResult($name, $value)
    {
        $this->results[$name] = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    protected function setResult($value)
    {
        $this->results = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param $index
     * @param $msg
     * @return $this
     */
    protected function addError($index, $msg)
    {
        if (!isset($this->errors[$index])) {
            $this->errors[$index] = null;
        }

        $this->errors[$index] = $msg;

        return $this;
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
     * @return $this
     */
    protected function setError(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
} 