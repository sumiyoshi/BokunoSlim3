<?php

namespace Core\Traits;

/**
 * Class Param
 * @package Core\Traits
 */
trait Param
{

    /**  @var array */
    private $params = array();

    /**
     * @param array $data
     * @return $this
     */
    public function setParams(array $data)
    {
        $this->params = $data;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParams()
    {
        return ((bool)$this->params);
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return $this->params;
    }

    protected function getParamsString(array $unset = array())
    {
        $params = array();
        foreach ($this->params as $key => $val) {

            if ((bool)$unset && array_search($key, $unset) !== false) {
                continue;
            }
            $params[] = $key . '=' . $val;

        }

        $params_string = implode('&', $params);
        return $params_string;
    }

    /**
     * @param $name
     * @return null
     */
    protected function param($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return null;
        }
    }
} 