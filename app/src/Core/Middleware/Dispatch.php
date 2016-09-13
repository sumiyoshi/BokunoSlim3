<?php

namespace Core\Middleware;

use Interop\Container\ContainerInterface;

/**
 * Class Dispatch
 *
 * @package Core\Http
 * @author sumiyoshi
 */
class Dispatch
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
     * @param $request
     * @param $response
     * @param $argument
     * @return \Slim\Http\Response
     */
    public function __invoke($request, $response, $argument)
    {
        $this->request = $request;
        $this->response = $response;

        #region 各種パラメータ取得
        $moduleName = (isset($argument['module'])) ? static::camelcase($argument['module'], true) : 'Fron';
        $controllerName = (isset($argument['controller'])) ? static::camelcase($argument['controller'], true) : 'Index';
        $actionName = (isset($argument['action'])) ? static::camelcase($argument['action'], true) : 'index';
        $id = (isset($argument['id'])) ? $argument['id'] : null;

        $template = $this->getTemplate($moduleName, $controllerName, $actionName);
        $controllerName = $this->getController($moduleName, $controllerName);
        $actionName = $this->getActionName($actionName);
        #endregion

        #region コントローラがない場合
        if (!class_exists($controllerName)) {
            return $this->notFound();
        }
        #endregion

        #region Injection初期設定
        $injection = new \Core\Service\Injection($controllerName);
        $injection
            ->setInjectionClass([
                'Slim\Http\Request' => $this->request,
                'Slim\Http\Response' => $this->response,
                'Interop\Container\ContainerInterface' => $this->container,
            ])
            ->setInjectionName([
                'id' => $id,
                'template' => $template
            ]);

        #endregion

        /** @var \Core\Application\Controller $controller */
        $controller = $injection->newInstance();

        #region アクションが存在しない場合
        if (!method_exists($controller, $actionName)) {
            return $this->notFound();
        }
        #endregion

        return $this->dispatch($injection, $controller, $actionName);
    }

    /**
     * @param $response
     * @param $template
     * @param $dto
     * @return \Slim\Http\Response
     */
    protected function render($response, $template, $dto)
    {
        if ($response === true) {
            $this->container->get('view')->render($this->response, $template, $dto);
            return $this->response;
        } elseif ($response === false) {
            return $this->notFound();
        } else {
            return $response;
        }
    }

    /**
     * @return \Slim\Http\Response
     */
    protected function notFound()
    {
        /** @var callable $notFoundHandler */
        $notFoundHandler = $this->container->get('notFoundHandler');
        return $notFoundHandler($this->request, $this->response);
    }

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

    /**
     * @param \Core\Service\Injection $injection
     * @param \Core\Application\Controller $controller
     * @param $actionName
     * @return \Slim\Http\Response
     */
    protected function dispatch($injection, $controller, $actionName)
    {
        $response = $injection->invoke($actionName, $controller);

        return $this->render($response, $controller->getTemplate(), $controller->dto);
    }

    /**
     * @param $moduleName
     * @param $controllerName
     * @param $actionName
     * @return string
     */
    protected function getTemplate($moduleName, $controllerName, $actionName)
    {
        $moduleName = lcfirst($moduleName);
        return "{$moduleName}/" . static::snakeCase($controllerName) . '/' . static::snakeCase($actionName) . '.twig';
    }

    /**
     * @param $moduleName
     * @param $controllerName
     * @return string
     */
    protected function getController($moduleName, $controllerName)
    {
        return "\\Http\\{$moduleName}\\Controller\\" . $controllerName . 'Controller';
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