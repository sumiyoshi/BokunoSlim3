<?php

use Core\Service\ErrorHandler;
use Core\Util\ArrayUtil;

$container = $app->getContainer();

$container['view'] = function ($c) {
    /** @var \Core\Application $config */
    $config = \Core\Application::getInstance();
    $view_config = $config->get('view');

    $view = new \Slim\Views\Twig($view_config['template_path'], $view_config);

    #region Add extensions
    if (ArrayUtil::hasKey('extension', $view_config)) {
        $extension = $view_config['extension'];
        foreach ($extension as $extension_class) {
            $view->addExtension(new $extension_class);
        }
    }
    #endregion

    return $view;
};

$container['notFoundHandler'] = function ($c) {
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return $response
     */
    return function ($request, $response) use ($c) {

        /** @var \Slim\Container $c */
        $view = $c->get('view');
        $view->render($response, '404.twig');

        return $response->withStatus(404);
    };
};

$container['errorHandler'] = function ($c) {

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param \Exception $e
     * @return $response
     */
    return function ($request, $response, \Exception $e) use ($c) {

        /** @var \Slim\Container $c */
        $view = $c->get('view');
        $response = $c->get('response');
        $view->render($response, '500.twig');

        ErrorHandler::errorLog($e->getMessage(), $e->getFile(), $e->getLine());
        ErrorHandler::send($e->getMessage(), $e->getFile(), $e->getLine());

        return $response->withStatus(500);
    };
};