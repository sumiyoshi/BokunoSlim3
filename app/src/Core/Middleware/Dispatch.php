<?php

namespace Core\Middleware;

use Interop\Container\ContainerInterface;

/**
 * Class Dispatch
 *
 * @package Core\Http
 * @author sumiyoshi
 */
abstract class Dispatch
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

        #region 各種パラメータ取得
        $controllerName = (isset($argument['controller'])) ? static::camelcase($argument['controller'], true) : 'Index';
        $actionName = (isset($argument['action'])) ? static::camelcase($argument['action'], true) : 'index';
        $id = (isset($argument['id'])) ? $argument['id'] : null;

        $template = $this->getTemplate($controllerName, $actionName);
        $controllerName = $this->getController($controllerName);
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
                'Interop\Container\ContainerInterface' => $this->container
            ])
            ->setInjectionName([
                'id' => $id,
                'template' => $template
            ]);

        #endregion

        /** @var \Http\Controller $controller */
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
        if (is_array($response)) {
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
     * @param \Http\Controller $controller
     * @param $actionName
     * @return \Slim\Http\Response
     */
    abstract protected function dispatch($injection, $controller, $actionName);

    /**
     * @param $controllerName
     * @param $actionName
     * @return string
     */
    abstract protected function getTemplate($controllerName, $actionName);

    /**
     * @param $controllerName
     * @return string
     */
    abstract protected function getController($controllerName);

    /**
     * @param $actionName
     * @return string
     */
    abstract protected function getActionName($actionName);

}