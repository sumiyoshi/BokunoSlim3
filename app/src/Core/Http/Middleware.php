<?php

namespace Core\Http;

use Interop\Container\ContainerInterface;

/**
 * Class AbstractMiddleware
 * @package Core\Http
 */
abstract class AbstractMiddleware
{

    /** @var  ContainerInterface */
    protected $container;

    /** @var  \Slim\Http\Request */
    protected $request;

    /** @var  \Slim\Http\Response */
    protected $response;

    /**
     * Middleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return $response
     */
    public function __invoke($request, $response, $argument)
    {
        $this->request = $request;
        $this->response = $response;

        $controllerName = (isset($argument['controller'])) ? static::camelcase($argument['controller'], true) : 'Index';
        $actionName = (isset($argument['action'])) ? static::camelcase($argument['action'], true) : 'index';
        $id = (isset($argument['id'])) ? $argument['id'] : null;

        return $this->dispatch($controllerName, $actionName, $id);
    }

    /**
     * @param $controllerName
     * @param $actionName
     * @param $id
     * @return mixed
     */
    abstract protected function dispatch($controllerName, $actionName, $id);

    /**
     * @param $str
     * @return string
     */
    protected static function snakeCase($str)
    {
        $str = preg_replace('/[a-z]+(?=[A-Z])|[A-Z]+(?=[A-Z][a-z])/', '\0_', $str);
        return strtolower($str);
    }

    /**
     * @param $str
     * @param bool $first
     * @return string
     */
    protected static function camelcase($str, $first = false)
    {
        if ($first) {
            return ucfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
        } else {
            return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
        }
    }

}