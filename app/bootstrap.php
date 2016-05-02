<?php

#region セッション
session_cache_limiter(false);
session_start();
#endregion

//シャットダウンハンドラー
register_shutdown_function(['\Core\Service\ErrorHandler', 'shutdownHandler']);

/** @var \Core\Application $config */
$config = \Core\Application::getInstance();

#region DB

if ($database = $config->get('database')) {

    $host = $database['host'];
    $db = $database['db'];
    $username = $database['username'];
    $password = $database['password'];

    \ORM::configure("mysql:host={$host};dbname={$db};charset=utf8", null, \ORM::DEFAULT_CONNECTION);
    \ORM::configure('username', $username, \ORM::DEFAULT_CONNECTION);
    if ($password) {
        \ORM::configure('password', $password, \ORM::DEFAULT_CONNECTION);
    }
    \ORM::configure('caching', true, \ORM::DEFAULT_CONNECTION);
    \ORM::configure('caching_auto_clear', true, \ORM::DEFAULT_CONNECTION);
    \ORM::configure('default_charset', "utf-8", \ORM::DEFAULT_CONNECTION);
}

\Model::$auto_prefix_models = '\\Component\\Model\\';
#endregion

$app = new \Slim\App();

#region ルーティング
$app->any("/{controller:[a-zA-Z_0-9]*}/[{action:[a-zA-Z_0-9]*}/{id:[0-9]*}]", \Http\Front\FrontMiddleware::class)->setName("front");
#endregion

#region ログの初期値セット
if ($log = $config->get('log')) {
    \Core\Service\Logger::$log_path = $log;
}
#endregion

# dependencies
include APP_ROOT . '/config/dependencies.php';

$app->run();