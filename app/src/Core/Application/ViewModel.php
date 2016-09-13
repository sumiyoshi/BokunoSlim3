<?php

namespace Core\Application;

use Slim\Http\Request;

/**
 * Class ViewModel
 *
 * @package Core\Application
 * @author sumiyoshi
 */
abstract class ViewModel
{

    public function __construct(Request $request)
    {
        $this->setProperty($request->getParams());
    }

    /**
     * @param array $data
     */
    protected function setProperty(array $data)
    {
        $properties = array_keys(get_object_vars($this));

        foreach ($properties as $property) {
            if (isset($data[$property])) {
                $this->{$property} = $this->_trim($data[$property]);
            }
        }
    }

    private function _trim($str)
    {
        $str = preg_replace('/^[ 　]+/u', '', $str);
        $str = preg_replace('/[ 　]+$/u', '', $str);
        return $str;
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        throw new \Exception("undefined: {$name}");
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return null;
    }
}