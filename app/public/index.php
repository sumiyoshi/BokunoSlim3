<?php

ini_set("display_errors", 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

define('SERVER_ROOT', dirname(dirname(dirname(__FILE__))));
define('APP_ROOT', SERVER_ROOT . '/app');

require SERVER_ROOT . '/vendor/autoload.php';

# define
require APP_ROOT . '/config/define.php';
# session
require APP_ROOT . '/config/session.php';
# database
require APP_ROOT . '/config/database.php';

$app = new \Slim\App();

# dependencies
include APP_ROOT . '/config/dependencies.php';

$app->any('/{controller:[a-zA-Z_0-9]*}/[{action:[a-zA-Z_0-9]*}/{id:[0-9]*}]', \App\Front\Middleware::class)->setName('front');

register_shutdown_function(['\Core\ErrorHandler', 'shutdownHandler']);

$app->run();

