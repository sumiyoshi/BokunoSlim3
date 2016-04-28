<?php

namespace Http\Front;

/**
 * Class FrontMiddleware
 * @package App\Front
 */
class FrontMiddleware extends \Http\Middleware
{

    /**
     * @param \Core\Service\Injection $injection
     * @param \Http\Controller $controller
     * @param $actionName
     * @return \Slim\Http\Response
     */
    protected function dispatch($injection, $controller, $actionName)
    {
        $response = $injection->invoke($actionName, $controller);

        return $this->render($response, $controller->getTemplate(), $controller->dto);
    }


    /**
     * @param $controllerName
     * @param $actionName
     * @return string
     */
    protected function getTemplate($controllerName, $actionName)
    {
        return 'front/' . static::snakeCase($controllerName) . '/' . static::snakeCase($actionName) . '.twig';
    }

    /**
     * @param $controllerName
     * @return string
     */
    protected function getController($controllerName)
    {
        return '\\Http\\Front\\Controller\\' . $controllerName . 'Controller';
    }

    /**
     * @param $actionName
     * @return string
     */
    protected function getActionName($actionName)
    {
        return $actionName . 'Action';
    }
}