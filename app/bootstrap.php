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

    \ORM::configure("mysql:host={$host};dbname={$db};charset=utf8");
    \ORM::configure('username', $username);
    if ($password) {
        \ORM::configure('password', $password);
    }
    \ORM::configure('caching', true);
    \ORM::configure('caching_auto_clear', true);
    \ORM::configure('default_charset', "utf-8");
}

\Model::$auto_prefix_models = '\\Component\\Data\\Model\\';
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