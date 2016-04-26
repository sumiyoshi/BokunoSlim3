<?php

namespace App\Front;

/**
 * Class Middleware
 * @package App\Front
 */
class Middleware extends \Core\Http\AbstractMiddleware
{
    /**
     * @param $controllerName
     * @param $actionName
     * @param $id
     * @return mixed
     */
    protected function dispatch($controllerName, $actionName, $id)
    {

        $template = 'front/' . static::snakeCase($controllerName) . '/' . static::snakeCase($actionName) . '.twig';
        $controllerName = '\\App\\Front\\Controller\\' . $controllerName . 'Controller';
        $actionName = $actionName . 'Action';

        if (!class_exists($controllerName)) {
            return $this->container->get('notFound');
        }

        $controller = new $controllerName();
        $controller->dto = [
            'template' => $template,
            'id' => $id
        ];

        if (!method_exists($controller, $actionName)) {
            return $this->container->get('notFound');
        }

        $response = $controller->{$actionName}($this->request, $this->response);

        if (is_array($response)) {
            $this->container->get('view')->render($this->response, $controller->dto['template'], $controller->dto);
            return $this->response;
        } else {
            return $response;
        }
    }
}