<?php

namespace Core;

use Core\Action\AbstractAction;

/**
 * Class CoreSlim
 * @package Core
 */
class CoreSlim extends \Slim\App
{
    /**
     * @return CoreSlim
     */
    public static function init()
    {
        return new \Core\CoreSlim();
    }


    public function run()
    {
        $this->dispatch();
        parent::run();
    }

    /**
     * @return $this
     */
    public function dispatch()
    {
        /** @var \Slim\Http\Request $request */
        $request = $this->getContainer()->get('request');

        list($route, $module_name, $controller_name, $action_name) = array_values($this->getContainer()->get('route_match'));

        if ($route) {

            $coreSlim = $this;

            /** @var \Slim\Views\Twig $view */
            $view = $this->getContainer()->get('view');

            $this->any($route, function ($request, $response) use ($coreSlim, $view, $module_name, $controller_name, $action_name) {
                /** @var \Slim\Http\Request $request */
                /** @var \Slim\Http\Response $response */

                $class_name = '\\App\\' . $module_name . '\\Action\\' . $controller_name . '\\' . $action_name . 'Action';

                if (!class_exists($class_name)) {
                    $this->pass();
                }

                /** @var AbstractAction $action */
                $action = new $class_name();
                $action->setApp($coreSlim);

                $variables = null;
                $action->init();
                $action->setTemplate($module_name, $controller_name, $action_name);
                switch ($request->getMethod()) {
                    case 'GET' :
                        if (!method_exists($action, 'get')) {
                            $this->pass(); // 404
                        }

                        call_user_func(array($action, 'get'));
                        break;
                    case 'POST' :
                        if (!method_exists($action, 'post')) {
                            $this->pass(); // 404
                        }

                        call_user_func(array($action, 'post'));
                        break;
                    default:
                        throw new \Exception('undefined HTTP method.');
                }

                if (method_exists($action, 'postDispatch')) {
                    $action->postDispatch();
                }

                if (!is_array($action->dto)) {
                    $variables = array();
                } else {
                    $variables = $action->dto;
                }

                $view->render($response, $action->template, $variables);
                return $response;

            })->setName($request->getUri()->getPath());
        }

        return $this;
    }

    private function pass()
    {

    }
}